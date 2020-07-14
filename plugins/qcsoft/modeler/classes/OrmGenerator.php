<?php namespace Qcsoft\Modeler\Classes;

use October\Rain\Database\Model;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;

class OrmGenerator
{
    protected static $modelStub;
    protected static $modelBaseStub;

    protected static function loadStubs()
    {
        if (!static::$modelStub || !static::$modelBaseStub)
        {
            static::$modelStub = file_get_contents(__DIR__ . '/ormgenerator/model.htm');
            static::$modelBaseStub = file_get_contents(__DIR__ . '/ormgenerator/modelbase.htm');
        }
    }

    public function generate(OrmSchema $schema, $onlyEntities = null)
    {
        $result = [];

        /** @var OrmModel $model */
        foreach ($schema->models as $model)
        {
            if (is_array($onlyEntities) && !in_array($model->entity->name, $onlyEntities))
            {
                continue;
            }

            $result[] = $this->generateForModel($schema, $model);
        }

        return $result;
    }

    public function generateForModel(OrmSchema $schema, OrmModel $model)
    {
        static::loadStubs();

        $pluginPath = plugins_path($schema->plugin->toFilesystemPath());

        $model->addClassUse(Model::class);

        \Event::fire('qcsoft.modeler.generateOrm', [$model]);

        asort($model->classUse);
        $model->classUse = array_values($model->classUse);

        ksort($model->properties);

        $result = (object)[];

        $result->entity_id = $model->entity->id;
        $result->modelPhpPath = "$pluginPath/models/" . $model->getClassname() . '.php';
        $result->modelPhpCode = \Twig::parse(static::$modelStub, ['model' => $model]);
        $result->modelBasePhpPath = "$pluginPath/modelsbase/" . $model->getClassname() . 'Base.php';
        $result->modelBasePhpCode = \Twig::parse(static::$modelBaseStub, ['model' => $model]);

        if (file_exists($result->modelBasePhpPath))
        {
            $oldBaseCode = file_get_contents($result->modelBasePhpPath);

            if ($oldBaseCode !== $result->modelBasePhpCode)
            {
                $result->type = 'updated';
                $result->modelBaseOldCode = $oldBaseCode;
                $result->modelBaseCodeDiff = $this->getDiff($oldBaseCode, $result->modelBasePhpCode);
            }
            else
            {
                $result->type = 'unchanged';
            }
        }
        else
        {
            $result->type = 'created';
        }

        return $result;
    }

    protected function getDiff($code, $newCode)
    {
        $builder = new DiffOnlyOutputBuilder(
            "--- Original\n+++ New\n"
        );

        $differ = new Differ(/*$builder*/);

        return $differ->diff($code, $newCode);
    }
}
