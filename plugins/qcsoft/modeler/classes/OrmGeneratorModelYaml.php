<?php namespace Qcsoft\Modeler\Classes;

use RainLab\Builder\Classes\PluginCode;

class OrmGeneratorModelYaml
{
//    /** @var PluginCode */
//    protected $pluginCodeObj;
//
//    /** @var SchemaDefinition */
//    protected $schema;
//
//    /**
//     * OrmGenerator constructor.
//     * @param string $pluginCodeObj
//     * @param SchemaDefinition $schema
//     */
//    public function __construct(string $pluginCode, SchemaDefinition $schema)
//    {
//        $this->pluginCodeObj = new PluginCode($pluginCode);
//        $this->schema = $schema;
//    }
//
//    public static function generateOrm($pluginCode, SchemaDefinition $schema)
//    {
//        return (new static($pluginCode, $schema))->generateOrmModels();
//    }
//
//    public function generateOrmModels()
//    {
//        $result = [];
//
//        $pluginPath = plugins_path($this->pluginCodeObj->toFilesystemPath());
//
//        Util::safedir("$pluginPath/models");
//        Util::safedir("$pluginPath/modelsbase");
//
////        $baseStub=file_get_contents($baseStubPath);
//        \Debugbar::info(__DIR__ . '/stubs/modelbase.htm');
////        $baseStubPath = plugins_path('qcsoft/modeler/classes/generator/templates/modelbase.htm');
////
////        $stubPath = plugins_path('qcsoft/modeler/classes/generator/templates/model.htm');
////        \Debugbar::info($baseStubPath);
//        return;
//        $models = $this->prepareOrmModels();
//
//        foreach ($models as $model)
//        {
//            /** @var OrmModel $model */
//
//            $resultItem = \Twig::parse(file_get_contents($baseStubPath), ['model' => $model]);
//
////            file_put_contents(
////                "$pluginPath/modelsbase/" . $model->getClassname() . 'Base.php',
////                \Twig::parse(
////                    file_get_contents($baseStubPath),
////                    ['model' => $model]
////                )
////            );
////
////            $modelPhpFilename = "$pluginPath/models/" . $model->getClassname() . '.php';
////
////            if (!file_exists($modelPhpFilename))
////            {
////                file_put_contents(
////                    $modelPhpFilename,
////                    \Twig::parse(
////                        file_get_contents($stubPath),
////                        ['model' => $model]
////                    )
////                );
////
////                $resultLog[] = $modelPhpFilename;
////            }
////
////            $modelDirPath = "$pluginPath/models/" . strtolower($model->getClassname());
////
////            if (!is_dir($modelDirPath))
////            {
////                mkdir($modelDirPath);
////            }
////
////            ////////////////////////////////////////////////////////////////////////////////
////            // Generate model fields yaml
////            ////////////////////////////////////////////////////////////////////////////////
////            $fieldsFilename = "$modelDirPath/fields.yaml";
////
////            if (!file_exists($fieldsFilename))
////            {
////                $fields = collect($model->columns)
////                    ->filter(function ($item)
////                    {
////                        return $item->column->name !== 'id';
////                    })
////                    ->mapWithKeys(function ($item)
////                    {
////
////                        /** @var Column $item */
////
////                        return [$item->column->name => [
////                            'label' => $item->column->name,
////                            'span'  => 'auto',
////                            'type'  => 'text',
////                        ]];
////                    })
////                    ->toArray();
////
////                file_put_contents($fieldsFilename, \Yaml::render(['fields' => $fields]));
////
////                $resultLog[] = $fieldsFilename;
////            }
////
////            ////////////////////////////////////////////////////////////////////////////////
////            // Generate model columns yaml
////            ////////////////////////////////////////////////////////////////////////////////
////            $columnsFilename = "$modelDirPath/columns.yaml";
////
////            if (!file_exists($columnsFilename))
////            {
////                $columns = collect($model->columns)->mapWithKeys(function ($item)
////                {
////
////                    /** @var Column $item */
////
////                    return [$item->column->name => [
////                        'label' => $item->column->name,
////                        'type'  => 'text',
////                    ]];
////                })
////                    ->toArray();
////
////                file_put_contents($columnsFilename, \Yaml::render(['columns' => $columns]));
////
////                $resultLog[] = $columnsFilename;
////            }
//        }
//
//        return $result;
//    }
//
//    protected function prepareOrmModels()
//    {
//        $result = [];
//
////        \Debugbar::info($this->schema);
//
//        foreach ($this->schema->entities as $entity)
//        {
//            $model = new OrmModel($this->pluginCodeObj, $entity);
//
//            \Event::fire('qcsoft.modeler.generateOrmModel', [$this->schema, $model]);
//
//            \Debugbar::info($model);
//
//            $result[] = $model;
//
////            $result[$entity->id]
//
////            $result[$entity->id]->addField((object)['qwer' => 'asdf']);
//
////            foreach ($entity->columns as $column)
////            {
////                $result[$table->name]->columns[] = new Column($column);
////            }
//        }
//
////        foreach ($this->schema->getRelations() as $relation)
////        {
////            foreach ($this->schema->getTables() as $table)
////            {
////                if ($table->name === $relation->table)
////                {
////                    $model = $result[$relation->table];
////
////                    $model->belongsTo[] = new OrmBelongsTo(
////                        $model,
////                        $relation->foreign_key,
////                        $result[$relation->foreign_table],
////                        $relation->foreign_table_key
////                    );
////                }
////                elseif ($table->name === $relation->foreign_table)
////                {
////                    $model = $result[$relation->foreign_table];
////
////                    $model->hasMany[] = new OrmHasMany(
////                        $model,
////                        $relation->foreign_table_key,
////                        $result[$relation->table],
////                        $relation->foreign_key
////                    );
////                }
////            }
////        }
////        \Debugbar::info($result);
//        return $result;
//    }
//
//    protected function plugindir($name)
//    {
//        $pluginPath = plugins_path($this->pluginCodeObj->toFilesystemPath());
//
//        if (!is_dir("$pluginPath/modelsbase"))
//        {
//            mkdir("$pluginPath/modelsbase");
//        }
//
//        if (!is_dir("$pluginPath/models"))
//        {
//            mkdir("$pluginPath/models");
//        }
//
//    }
//
}
