<?php namespace Ydnnov\Crudgen\Classes;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use DbmlParser\Parser;
use RainLab\Builder\Classes\ControllerModel;
use RainLab\Builder\Classes\PluginCode;

class Generator
{
    /** @var string */
    protected $pluginCode;

    /** @var string */
    protected $sourceDbmlFilePath;

    /** @var Parser */
    protected $parser;

    /** @var PluginCode */
    protected $pluginCodeObj;

    /**
     * Generator constructor.
     * @param string $pluginCode
     * @param string $sourceDbmlFilePath
     */
    public function __construct($pluginCode, $sourceDbmlFilePath)
    {
        $this->pluginCode = $pluginCode;
        $this->sourceDbmlFilePath = $sourceDbmlFilePath;

        $this->parser = new Parser($this->sourceDbmlFilePath);
        $this->pluginCodeObj = new PluginCode($pluginCode);
    }

    public function generateMigrations()
    {
        $methodMap = [
            'int'     => 'integer',
            'varchar' => 'string',
            'text'    => 'text',
        ];

        $tablePrefix = $this->pluginCodeObj->toDatabasePrefix();

        $result = [];

        foreach ($this->parser->tables as $table)
        {
            $migrationVars = [
                'table_name'   => $table->name,
                'table_prefix' => $tablePrefix,
                'columns'      => [],
            ];

            foreach ($table->columns as $column)
            {
                /** @var Parser\Column $column */

                if ($column->PK && $column->Increment)
                {
                    $method = 'increments';
                }
                else
                {
                    $method = $methodMap[$column->type];
                }

                $migrationVars['columns'][] = [
                    'name'     => $column->name,
                    'method'   => $method,
                    'nullable' => ($column->NotNull || $method === 'increments') ? '' : '->nullable()',
                ];
            }

            $result[] = \Twig::parse(
                file_get_contents(plugins_path('ydnnov/crudgen/classes/generator/templates/migrations.htm')),
                $migrationVars
            );
        }

        return $result;
    }

    public function generateModels()
    {
        $models = [];

        foreach ($this->parser->tables as $table)
        {
            /** @var Parser\Table $table */

            $models[$table->name] = new DbmlModel($this->pluginCodeObj, $table->name);

            foreach ($table->columns as $column)
            {
                $models[$table->name]->columns[] = new DbmlColumn($column);
            }
        }

        foreach ($this->parser->relations as $relation)
        {
            $relationTypeStr = $relation->type->__toString();

            if ($relationTypeStr === '>')
            {
                $thisTableName = $relation->table->name;
                $thisColumnName = $relation->column->name;
                $foreignTableName = $relation->foreign_table->name;
                $foreignColumnName = $relation->foreign_column->name;
            }
            elseif ($relationTypeStr === '<')
            {
                $thisTableName = $relation->foreign_table->name;
                $thisColumnName = $relation->foreign_column->name;
                $foreignTableName = $relation->table->name;
                $foreignColumnName = $relation->column->name;
            }
            else
            {
                throw new \Exception("Relation '$relationTypeStr' not implemented yet!");
            }

            foreach ($this->parser->tables as $table)
            {
                if ($thisTableName === $table->name)
                {
                    $model = $models[$thisTableName];

                    $model->belongsTo[] = new DbmlBelongsTo(
                        $model,
                        $thisColumnName,
                        $models[$foreignTableName],
                        $foreignColumnName
                    );
                }
                elseif ($foreignTableName === $table->name)
                {
                    $model = $models[$foreignTableName];

                    $model->hasMany[] = new DbmlHasMany(
                        $model,
                        $foreignColumnName,
                        $models[$thisTableName],
                        $thisColumnName
                    );
                }
            }
        }

        $baseStubPath = plugins_path('ydnnov/crudgen/classes/generator/templates/modelbase-trait.htm');

        $stubPath = plugins_path('ydnnov/crudgen/classes/generator/templates/model.htm');

        $pluginPath = plugins_path($this->pluginCodeObj->toFilesystemPath());

        if (!is_dir("$pluginPath/modelsbase"))
        {
            mkdir("$pluginPath/modelsbase");
        }

        if (!is_dir("$pluginPath/models"))
        {
            mkdir("$pluginPath/models");
        }

        foreach ($models as $model)
        {
            /** @var DbmlModel $model */

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

                    /** @var DbmlColumn $item */

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

                    /** @var DbmlColumn $item */

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

        foreach ($this->parser->tables as $table)
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
        foreach ($this->parser->tables as $table)
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

            $dbmlModel = new DbmlModel($this->pluginCodeObj, $table->name);

            $model->fill([
                'controller'         => $controllerClassname,
                'behaviors'          => [
                    ListController::class,
                    FormController::class,
                ],
                'baseModelClassName' => $dbmlModel->getClassname(),
                'menuItem'           => $this->getMainMenuKey() . '||' . $this->getSideMenuKey($table->name),
            ]);

            $model->save();
        }
    }

    public function prettyPrint()
    {
        return \Twig::parse(
            file_get_contents(plugins_path('ydnnov/crudgen/classes/generator/templates/prettyprint.htm')),
            ['tables' => $this->parser->tables]
        );
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
