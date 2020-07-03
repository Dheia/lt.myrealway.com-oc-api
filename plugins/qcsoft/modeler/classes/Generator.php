<?php namespace Qcsoft\Modeler\Classes;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use RainLab\Builder\Classes\ControllerModel;
use RainLab\Builder\Classes\PluginCode;

class Generator
{
    /** @var PluginCode */
    protected $pluginCodeObj;

    /** @var DbmlSchema */
    protected $schema;

    /**
     * Generator constructor.
     * @param string $pluginCode
     * @param string $sourceDbmlFilePath
     */
    public function __construct($pluginCode, $sourceDbmlFilePath)
    {
        $this->pluginCodeObj = new PluginCode($pluginCode);

        $this->schema = new DbmlSchema($sourceDbmlFilePath);
    }

    public function generateMenus()
    {
        $pluginYamlPath = plugins_path($this->pluginCodeObj->toFilesystemPath() . '/plugin.yaml');

        $pluginConfig = \Yaml::parseFile($pluginYamlPath);

        if (isset($pluginConfig['navigation']))
        {
            return;
        }

        $mainMenuKey = $this->getMainMenuKey();

        $pathPrefix = $this->pluginCodeObj->toUrl();

        $sideMenuItems = [];

        foreach ($this->schema->getTables() as $table)
        {
            $parts = array_map(['\Str', 'plural'], explode('_', $table->name));

            $sideMenuItems[$this->getSideMenuKey($table->name)] = [
                'label' => implode(' ', array_map(['\Str', 'ucfirst'], $parts)),
                'url'   => $pathPrefix . '/' . implode('', $parts),
                'icon'  => 'icon-sitemap',
            ];
        }

        $navigation = [
            $mainMenuKey => [
                'label'    => $this->pluginCodeObj->getPluginCode(),
                'url'      => array_first($sideMenuItems)['url'],
                'icon'     => 'icon-life-ring',
                'sideMenu' => $sideMenuItems,
            ]
        ];

        $pluginConfig['navigation'] = $navigation;

        file_put_contents($pluginYamlPath, \Yaml::render($pluginConfig));
    }

    public function generateControllers()
    {
        foreach ($this->schema->getTables() as $table)
        {
            $parts = array_map(['\Str', 'plural'], explode('_', $table->name));

            $controllerClassname = implode('', array_map(['\Str', 'ucfirst'], $parts));

            $controllerQualifiedClassname = $this->pluginCodeObj->toPluginNamespace() . '\\Controllers\\' . $controllerClassname;

            if (class_exists($controllerQualifiedClassname))
            {
                continue;
            }

            $model = new ControllerModel();

            $model->setPluginCode($this->pluginCodeObj->toCode());

            $ormModel = new OrmModel($this->pluginCodeObj, $table->name);

            $model->fill([
                'controller'         => $controllerClassname,
                'behaviors'          => [
                    ListController::class,
                    FormController::class,
                ],
                'baseModelClassName' => $ormModel->getClassname(),
                'menuItem'           => $this->getMainMenuKey() . '||' . $this->getSideMenuKey($table->name),
            ]);

            $model->save();
        }
    }

    protected function getMainMenuKey()
    {
        return 'main-menu-' . strtolower($this->pluginCodeObj->getPluginCode());
    }

    protected function getSideMenuKey($tableName)
    {
        $parts = array_map(['\Str', 'plural'], explode('_', $tableName));

        return 'side-menu-' . implode('-', $parts);
    }

}
