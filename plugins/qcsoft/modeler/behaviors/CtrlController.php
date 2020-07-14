<?php namespace Qcsoft\Modeler\Behaviors;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\ControllerBehavior;
use Qcsoft\Modeler\Classes\OrmField;
use Qcsoft\Modeler\Classes\OrmModel;
use Qcsoft\Modeler\Classes\OrmSchema;
use RainLab\Builder\Classes\ControllerModel;

class CtrlController extends ControllerBehavior
{
    public function onCtrlGetStatusAll()
    {
        /** @var OrmSchema $schema */
        $schema = $this->controller->ormSchema;

        $result = [];

        $sideMenu = $schema->pluginConfig['navigation'][$schema->getMainMenuKey()]['sideMenu'];

        /** @var OrmModel $model */
        foreach ($schema->models as $model)
        {
            $modelDirPath = $model->getModelDirPath();

            $result[] = [
                'name'       => $model->getControllerClassname(),
                'entityName' => $model->entity->name,
                'menu'       => isset($sideMenu[$model->getSideMenuKey()]),
                'controller' => class_exists($model->getControllerQualifiedClassname()),
                'fields'     => file_exists("$modelDirPath/fields.yaml"),
                'columns'    => file_exists("$modelDirPath/columns.yaml"),
            ];
        }

        return json_encode($result);
    }

    public function onCtrlAction()
    {
        $type = \Request::input('type');
        $entityName = \Request::input('entity');

        $model = $this->controller->ormSchema->models
            ->first(function ($item) use ($entityName)
            {
                return $item->entity->name === $entityName;
            });

        $methodName = 'action' . ucfirst($type);

//        \Debugbar::info($methodName . ': ' . $model->entity->name);

        return $this->$methodName($model);
    }

    public function actionMenu(OrmModel $model)
    {
        return 'TODO';
//        /** @var OrmSchema $schema */
//        $schema = $this->controller->ormSchema;
//
//        $result = [];
//
//        \Debugbar::info($model);
//
//        $sideMenu = $schema->pluginConfig['navigation'][$schema->getMainMenuKey()]['sideMenu'];
//
//        /** @var OrmModel $model */
//        foreach ($schema->models as $model)
//        {
//            $modelDirPath = $model->getModelDirPath();
//
//            $result[] = [
//                'name'       => $model->getControllerClassname(),
//                'entityName' => $model->entity->name,
//                'menu'       => isset($sideMenu[$model->getSideMenuKey()]),
//                'controller' => class_exists($model->getControllerQualifiedClassname()),
//                'fields'     => file_exists("$modelDirPath/fields.yaml"),
//                'columns'    => file_exists("$modelDirPath/columns.yaml"),
//            ];
//        }
    }

    protected function actionController(OrmModel $model)
    {
        if (file_exists($controllerFilepath = $model->getControllerPhpFilepath()))
        {
            return '{"status": "alreadyExist", "value": true}';
        }

        $controllerModel = new ControllerModel();

        $controllerModel->setPluginCode($model->schema->plugin->toCode());

        $controllerModel->fill([
            'controller'         => $model->getControllerClassname(),
            'behaviors'          => [
                ListController::class,
                FormController::class,
            ],
            'baseModelClassName' => $model->getClassname(),
            'menuItem'           => $model->schema->getMainMenuKey() . '||' . $model->getSideMenuKey(),
        ]);

        $controllerModel->save();

        return '{"status": "createdSuccess", "value": true}';
    }

    protected function actionColumns(OrmModel $model)
    {
        if (!$columnsFilepath = $model->createModelFile('columns.yaml'))
        {
            return '{"status": "alreadyExist", "value": true}';
        }

        $columns = collect($model->fields)->mapWithKeys(
            function (OrmField $item)
            {
                return [$item->attribute->name => [
                    'label' => $item->attribute->name,
                    'type'  => 'text',
                ]];
            })
            ->toArray();

        file_put_contents($columnsFilepath, \Yaml::render(['columns' => $columns]));

        return '{"status": "createdSuccess", "value": true}';
    }

    protected function actionFields(OrmModel $model)
    {
        if (!$fieldsFilepath = $model->createModelFile('fields.yaml'))
        {
            return '{"status": "alreadyExist", "value": true}';
        }

        $fields = collect($model->fields)
            ->filter(function ($item)
            {
                return $item->attribute->name !== 'id';
            })
            ->mapWithKeys(function (OrmField $item)
            {
                return [$item->attribute->name => [
                    'label' => $item->attribute->name,
                    'span'  => 'auto',
                    'type'  => 'text',
                ]];
            })
            ->toArray();

        file_put_contents($fieldsFilepath, \Yaml::render(['fields' => $fields]));

        return '{"status": "createdSuccess", "value": true}';
    }

}
