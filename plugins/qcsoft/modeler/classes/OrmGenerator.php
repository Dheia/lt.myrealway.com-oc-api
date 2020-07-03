<?php namespace Qcsoft\Modeler\Classes;

use RainLab\Builder\Classes\PluginCode;

class OrmGenerator
{
    /** @var PluginCode */
    public $pluginCodeObj;

    /** @var SchemaDefinition */
    public $schemaDefinition;

    /** @var array */
    public $models;

    /**
     * OrmGenerator constructor.
     * @param string $pluginCodeObj
     * @param SchemaDefinition $schemaDefinition
     */
    public function __construct(string $pluginCode, SchemaDefinition $schemaDefinition)
    {
        $this->pluginCodeObj = new PluginCode($pluginCode);
        $this->schemaDefinition = $schemaDefinition;
    }

    public function generateOrmModels($callback, $only = [])
    {
        $result = [];

        $pluginPath = plugins_path($this->pluginCodeObj->toFilesystemPath());

        Util::safedir("$pluginPath/models");
        Util::safedir("$pluginPath/modelsbase");

        $modelStub = file_get_contents(__DIR__ . '/stubs/model.htm');
        $modelBaseStub = file_get_contents(__DIR__ . '/stubs/modelbase.htm');

        $this->prepareSchema();

        /** @var OrmModel $model */
        foreach ($this->models as $model)
        {
            if (!empty($only) && !in_array($model->entity->name, $only))
            {
                continue;
            }

            $callback($model);

            $modelBaseCode = \Twig::parse($modelBaseStub, ['model' => $model]);
            $modelCode = \Twig::parse($modelStub, ['model' => $model]);

            $result[] = [
                'modelPath'     => "$pluginPath/models/" . $model->getClassname() . '.php',
                'modelCode'     => $modelCode,
                'modelBasePath' => "$pluginPath/modelsbase/" . $model->getClassname() . 'Base.php',
                'modelBaseCode' => $modelBaseCode,
            ];
        }

        return $result;
    }

    protected function prepareSchema()
    {
        $this->models = [];

        $fields = [];

        foreach ($this->schemaDefinition->entities as $entity)
        {
            $model = new OrmModel($this, $entity);

            foreach ($this->schemaDefinition->attributes as $attribute)
            {
                if ($attribute->entity_id === $entity->id)
                {
                    $field = new OrmField($model, $attribute);

                    $model->fields[] = $field;

                    $fields[$attribute->id] = $field;
                }
            }

            $this->models[$entity->id] = $model;
        }

        $relations = [];

        foreach ($this->schemaDefinition->relations as $relation)
        {
            /** @var OrmField $fieldFrom */
            $fieldFrom = $fields[$relation->attribute_from_id];

            /** @var OrmField $fieldTo */
            $fieldTo = $fields[$relation->attribute_to_id];

            /** @var OrmModel $modelFrom */
            $modelFrom = $this->models[$fieldFrom->model->entity->id];

            /** @var OrmModel $modelTo */
            $modelTo = $this->models[$fieldTo->model->entity->id];

            $relation = new OrmRelation($fieldFrom, $fieldTo, $relation);

            $modelFrom->relationsFrom[] = $relation;

            $modelTo->relationsTo[] = $relation;

            $relations[] = $relation;
        }
    }

}
