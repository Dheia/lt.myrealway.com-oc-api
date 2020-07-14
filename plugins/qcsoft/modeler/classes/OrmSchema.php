<?php namespace Qcsoft\Modeler\Classes;

use Illuminate\Support\Collection;
use RainLab\Builder\Classes\PluginCode;

class OrmSchema
{
    /** @var PluginCode */
    public $plugin;

    /** @var SchemaDefinition */
    public $schemaDefinition;

    /** @var Collection */
    public $models;

    /** @var array */
    public $pluginConfig;

    /**
     * OrmSchema constructor.
     * @param PluginCode $plugin
     * @param SchemaDefinition $schemaDefinition
     */
    public function __construct(PluginCode $plugin, SchemaDefinition $schemaDefinition)
    {
        $this->plugin = $plugin;
        $this->schemaDefinition = $schemaDefinition;

        $this->build();

        $this->buildNormalizedPluginConfig();
    }

    public function getMainMenuKey()
    {
        return 'main-menu-' . strtolower($this->plugin->getPluginCode());
    }

    public function buildNormalizedPluginConfig()
    {
        $this->pluginConfig = \Yaml::parseFile($this->getPluginYamlFilepath());

        $mainMenuKey = $this->getMainMenuKey();

        $navigation = array_get($this->pluginConfig, 'navigation', []);

        $mainMenu = array_get($navigation, $mainMenuKey, []);
        $mainMenu['label'] = array_get($mainMenu, 'label', $this->plugin->getPluginCode());
        $mainMenu['icon'] = array_get($mainMenu, 'icon', 'icon-life-ring');
        $mainMenu['sideMenu'] = array_get($mainMenu, 'sideMenu', (object)[]);

        $this->pluginConfig['navigation'][$mainMenuKey] = $mainMenu;
    }

    public function writePluginConfig()
    {
        file_put_contents($this->getPluginYamlFilepath(), \Yaml::render($this->pluginConfig));
    }

    public function getPluginYamlFilepath()
    {
        return plugins_path($this->plugin->toFilesystemPath() . '/plugin.yaml');
    }

    public function build()
    {
        $this->models = collect();

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

        return $this->models;
    }

}
