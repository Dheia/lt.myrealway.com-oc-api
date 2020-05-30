<?php namespace Ydnnov\Crudgen\Classes;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use RainLab\Builder\Classes\ControllerModel;
use RainLab\Builder\Classes\DatabaseTableModel;
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

    public function generateMigrationModels()
    {
        $result = [];

        $tablePrefix = $this->pluginCodeObj->toDatabasePrefix();

        foreach ($this->schema->getTables() as $table)
        {
            $model = new DatabaseTableModel();

            $model->setPluginCode($this->pluginCodeObj->toCode());

            $model->name = $tablePrefix . '_' . $table->name;

            $model->columns = [];

            foreach ($table->columns as $column)
            {
                $model->columns[] = [
                    'name'           => $column->name,
                    'type'           => $column->type,
                    'length'         => '',
                    'unsigned'       => '',
                    'allow_null'     => $column->nullable ? '1' : '',
                    'auto_increment' => $column->auto_increment ? '1' : '',
                    'primary_key'    => $column->pk ? '1' : '',
                    'default'        => '',
                ];
            }

            $result[] = $model->generateCreateOrUpdateMigration();
        }

        return $result;
    }

    public function generateOrmModels()
    {
        $pluginPath = plugins_path($this->pluginCodeObj->toFilesystemPath());

        if (!is_dir("$pluginPath/modelsbase"))
        {
            mkdir("$pluginPath/modelsbase");
        }

        if (!is_dir("$pluginPath/models"))
        {
            mkdir("$pluginPath/models");
        }

        $baseStubPath = plugins_path('ydnnov/crudgen/classes/generator/templates/modelbase.htm');

        $stubPath = plugins_path('ydnnov/crudgen/classes/generator/templates/model.htm');

        $models = $this->prepareOrmModels();

        foreach ($models as $model)
        {
            /** @var OrmModel $model */

            file_put_contents(
                "$pluginPath/modelsbase/" . $model->getClassname() . 'Base.php',
                \Twig::parse(
                    file_get_contents($baseStubPath),
                    ['model' => $model]
                )
            );

            if (!file_exists("$pluginPath/models/" . $model->getClassname() . '.php'))
            {
                file_put_contents(
                    "$pluginPath/models/" . $model->getClassname() . '.php',
                    \Twig::parse(
                        file_get_contents($stubPath),
                        ['model' => $model]
                    )
                );
            }

            if (!is_dir("$pluginPath/models/" . strtolower($model->getClassname())))
            {
                mkdir("$pluginPath/models/" . strtolower($model->getClassname()));
            }

            ////////////////////////////////////////////////////////////////////////////////
            // Generate model fields yaml
            ////////////////////////////////////////////////////////////////////////////////
            $fieldsFilename = "$pluginPath/models/" . strtolower($model->getClassname()) . '/fields.yaml';

            if (!file_exists($fieldsFilename))
            {
                $fields = collect($model->columns)->mapWithKeys(function ($item) {

                    /** @var Column $item */

                    return [$item->column->name => [
                        'label' => $item->column->name,
                        'span'  => 'auto',
                        'type'  => 'text',
                    ]];
                })
                    ->toArray();

                file_put_contents($fieldsFilename, \Yaml::render(['fields' => $fields]));
            }

            ////////////////////////////////////////////////////////////////////////////////
            // Generate model columns yaml
            ////////////////////////////////////////////////////////////////////////////////
            $columnsFilename = "$pluginPath/models/" . strtolower($model->getClassname()) . '/columns.yaml';

            if (!file_exists($columnsFilename))
            {
                $columns = collect($model->columns)->mapWithKeys(function ($item) {

                    /** @var Column $item */

                    return [$item->column->name => [
                        'label' => $item->column->name,
                        'type'  => 'text',
                    ]];
                })
                    ->toArray();

                file_put_contents($columnsFilename, \Yaml::render(['columns' => $columns]));
            }
        }
    }

    protected function prepareOrmModels()
    {
        $result = [];

        foreach ($this->schema->getTables() as $table)
        {
            $result[$table->name] = new OrmModel($this->pluginCodeObj, $table->name);

            foreach ($table->columns as $column)
            {
                $result[$table->name]->columns[] = new Column($column);
            }
        }

        foreach ($this->schema->getRelations() as $relation)
        {
            foreach ($this->schema->getTables() as $table)
            {
                if ($table->name === $relation->table)
                {
                    $model = $result[$relation->table];

                    $model->belongsTo[] = new OrmBelongsTo(
                        $model,
                        $relation->foreign_key,
                        $result[$relation->foreign_table],
                        $relation->foreign_table_key
                    );
                }
                elseif ($table->name === $relation->foreign_table)
                {
                    $model = $result[$relation->foreign_table];

                    $model->hasMany[] = new OrmHasMany(
                        $model,
                        $relation->foreign_table_key,
                        $result[$relation->table],
                        $relation->foreign_key
                    );
                }
            }
        }

        return array_values($result);
    }

    public function generateMenus()
    {
        $pluginYamlPath = plugins_path($this->pluginCodeObj->toFilesystemPath() . '/plugin.yaml');

        $pluginConfig = \Yaml::parseFile($pluginYamlPath);

        if (isset($pluginConfig['navigation']))
        {
            throw new \Exception('Plugin already has menu configuration');
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
