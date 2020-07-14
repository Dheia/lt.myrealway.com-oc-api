<?php namespace Qcsoft\Modeler\Behaviors;

use Backend\Classes\ControllerBehavior;
use Qcsoft\Modeler\Classes\OrmModel;
use Qcsoft\Modeler\Classes\OrmSchema;
use RainLab\Builder\Classes\DatabaseTableModel;

class MigrationsController extends ControllerBehavior
{
    public function onMigrationsLoadAll()
    {
        /** @var OrmSchema $schema */
        $schema = $this->controller->ormSchema;

        return $this->generate($schema);
    }

    public function onMigrationsApplyAll()
    {
        /** @var OrmSchema $schema */
        $schema = $this->controller->ormSchema;

//        $result = $this->generate($schema, ['filter', 'filteroption']);
        $result = $this->generate($schema);

        $fn = eval($result);

        $fn();
    }

    public function generate(OrmSchema $schema, $onlyEntities = null)
    {
        $tablePrefix = $schema->plugin->toDatabasePrefix();

        $result = [];

        /** @var OrmModel $model */
        foreach ($schema->models as $model)
        {
            if (is_array($onlyEntities) && !in_array($model->entity->name, $onlyEntities))
            {
                continue;
            }

            $dbTableModel = new DatabaseTableModel();

            if (\Schema::hasTable($tablePrefix . '_' . $model->entity->name))
            {
                $dbTableModel->load($tablePrefix . '_' . $model->entity->name);
            }
            else
            {
                $dbTableModel->name = $tablePrefix . '_' . $model->entity->name;
            }

            $dbTableModel->setPluginCode($schema->plugin->toCode());

            \Event::fire('qcsoft.modeler.generateMigration', [$dbTableModel, $model]);

            if ($migration = $dbTableModel->generateCreateOrUpdateMigration())
            {
                $migrationCode = preg_replace(
                    '/(\s*public function\s+up\s*\(\)\s*\{)(.+)public\s+?function\s+down.+$/s',
                    "$2",
                    $migration->code
                );

                $migrationCode = str_replace_last('}', '', $migrationCode);

                $migrationCode = trim($migrationCode);

                $result[] = $migrationCode;
            }
        }

        return "return function() {\n" . implode("\n", $result) . "\n};";
    }

}
