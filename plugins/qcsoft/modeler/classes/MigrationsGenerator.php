<?php namespace Qcsoft\Modeler\Classes;

use RainLab\Builder\Classes\DatabaseTableModel;

class MigrationsGenerator
{
    public static function generateMigrations($pluginCode, SchemaDefinition $schemaDefinition, $onlyEntities = null)
    {
        $tablePrefix = strtolower(str_replace('.', '_', $pluginCode));

        $result = [];

        foreach ($schemaDefinition->entities as $entity)
        {
            if (is_array($onlyEntities) && !in_array($entity->name, $onlyEntities))
            {
                continue;
            }

            $model = new DatabaseTableModel();

            if (\Schema::hasTable($tablePrefix . '_' . $entity->name))
            {
                $model->load($tablePrefix . '_' . $entity->name);
            }
            else
            {
                $model->name = $tablePrefix . '_' . $entity->name;
            }

            $model->setPluginCode($pluginCode);

            $entityAttributes = collect($schemaDefinition->attributes)->filter(function ($item) use ($entity)
            {
                return $item->entity_id == $entity->id;
            });

            \Event::fire('qcsoft.modeler.generateMigration', [$model, $entity, $entityAttributes]);

            if ($migration = $model->generateCreateOrUpdateMigration())
            {
                $result[] = preg_replace(
                    '/(function\s+up\s*\(\))(.+)public\s+?function\s+down.+$/s',
                    "function {$entity->name}_{$entity->id}() $2",
                    $migration->code
                );
            }
        }

        return implode('', $result);
    }

}
