<?php namespace Qcsoft\Modeler\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use October\Rain\Database\Collection;
use Qcsoft\Modeler\Behaviors\CtrlController;
use Qcsoft\Modeler\Behaviors\MigrationsController;
use Qcsoft\Modeler\Behaviors\OrmController;
use Qcsoft\Modeler\Classes\OrmSchema;
use Qcsoft\Modeler\Classes\SchemaDefinition;
use Qcsoft\Modeler\Models\Attribute;
use Qcsoft\Modeler\Models\Entity;
use Qcsoft\Modeler\Models\Relation;
use RainLab\Builder\Classes\PluginCode;

class Home extends Controller
{
    public $implement = [MigrationsController::class, OrmController::class, CtrlController::class];

    protected static $pl = [
        'entity'    => 'entities',
        'attribute' => 'attributes',
        'relation'  => 'relations',
    ];

    /** @var OrmSchema */
    public $ormSchema;

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.Modeler', 'main-menu-modeler');

        $this->addCss('/plugins/qcsoft/modeler/assets/diff2html.min.css');
//        $this->addCss('/plugins/qcsoft/modeler/assets/diff2html.min.js');
//        $this->addJs('http://178.19.16.34:8080/main.js');
        $this->addJs('/plugins/qcsoft/modeler/assets/dist/main.js');

        $this->ormSchema = new OrmSchema(
            new PluginCode('Qcsoft.App'),
            $this->getSchemaDefinition('database')
        );
    }

    public function index()
    {
        $this->vars['schema'] = htmlspecialchars(json_encode([
            'entities'   => \Qcsoft\Modeler\Models\Entity::with('options')->get(),
            'attributes' => \Qcsoft\Modeler\Models\Attribute::orderBy('sort_order')->get(),
            'relations'  => \Qcsoft\Modeler\Models\Relation::get(),
        ]));
    }

    public function onSave()
    {
        $requestedData = \Request::input('data');

        $requestedEntities = $requestedData['entities'];

        /** @var Collection $existingEntities */
        $existingEntities = Entity::orderBy('id')->get();

        foreach ($requestedEntities as $requestedEntity)
        {
            $existingEntity = $existingEntities->firstWhere('id', $requestedEntity['id']);
            $existingEntity->x = $requestedEntity['x'];
            $existingEntity->y = $requestedEntity['y'];
            $existingEntity->width = $requestedEntity['width'];
            $existingEntity->save();
        }
    }

    public function getSchemaDefinition($from)
    {
        if ($from === 'request')
        {
            $requestData = \Request::input('data');

            $entitiesDef = collect($requestData['entities'])
                ->map(function ($item)
                {
                    if (!isset($item['options']))
                    {
                        $item['options'] = [];
                    }

                    return (object)$item;
                });

            $attributesDef = collect($requestData['attributes'])
                ->map(function ($item)
                {
                    return (object)$item;
                });

            $relationsDef = collect($requestData['relations'])
                ->map(function ($item)
                {
                    return (object)$item;
                });
        }
        elseif ($from === 'database')
        {
            $entitiesDef = Entity::with('options')->get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                });

            $attributesDef = Attribute::orderBy('sort_order')->get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                });

            $relationsDef = Relation::get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                });
        }
        else
        {
            throw new \Exception("getSchemaDefinition($from) source should be database or request");
        }

        return new SchemaDefinition($entitiesDef, $attributesDef, $relationsDef);
    }

//    public function onGetControllersAndMenus()
//    {
//        $schemaDefinition = $this->getSchemaDefinition('database');
//        \Debugbar::info($schemaDefinition);
//        return;
//
//        foreach ($schemaDefinition->entities as $entity)
//        {
//            $parts = array_map(['\Str', 'plural'], explode('_', $entity->name));
//
//            $controllerClassname = implode('', array_map(['\Str', 'ucfirst'], $parts));
//
//            $controllerQualifiedClassname = $this->pluginCodeObj->toPluginNamespace() . '\\Controllers\\' . $controllerClassname;
//
//            if (class_exists($controllerQualifiedClassname))
//            {
//                continue;
//            }
//
//            $model = new ControllerModel();
//
//            $model->setPluginCode($this->pluginCodeObj->toCode());
//
//            $ormModel = new \Qcsoft\Crudgen\Classes\OrmModel($this->pluginCodeObj, $table->name);
//
//            $model->fill([
//                'controller'         => $controllerClassname,
//                'behaviors'          => [
//                    ListController::class,
//                    FormController::class,
//                ],
//                'baseModelClassName' => $ormModel->getClassname(),
//                'menuItem'           => $this->getMainMenuKey() . '||' . $this->getSideMenuKey($table->name),
//            ]);
//
//            $model->save();
//        }
//    }

}
