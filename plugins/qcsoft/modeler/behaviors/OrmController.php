<?php namespace Qcsoft\Modeler\Behaviors;

use Backend\Classes\ControllerBehavior;
use Qcsoft\Modeler\Classes\OrmModel;
use Qcsoft\Modeler\Classes\OrmSchema;
use Qcsoft\Modeler\Classes\OrmGenerator;
use Qcsoft\Modeler\Classes\Util;

class OrmController extends ControllerBehavior
{
    /** @var array */
    public $models;

    public function onOrmApply()
    {
        $entityId = (int)\Request::input('entityId');

        /** @var OrmSchema $schema */
        $schema = $this->controller->ormSchema;

        $model = $schema->models->first(function (OrmModel $item) use ($entityId)
        {
            return $item->entity->id === $entityId;
        });

        $generated = (new OrmGenerator)->generate($this->controller->ormSchema, [$model->entity->name]);

        $generated = $generated[0];

        $pluginPath = plugins_path($schema->plugin->toFilesystemPath());

        Util::safedir("$pluginPath/models");
        Util::safedir("$pluginPath/modelsbase");

        file_put_contents($generated->modelBasePhpPath, $generated->modelBasePhpCode);

        if (!file_exists($generated->modelPhpPath))
        {
            file_put_contents($generated->modelPhpPath, $generated->modelPhpCode);
        }

        return json_encode((new OrmGenerator)->generate($this->controller->ormSchema));
    }

    public function onOrmLoadAll()
    {
        return json_encode((new OrmGenerator)->generate($this->controller->ormSchema));
    }

}
