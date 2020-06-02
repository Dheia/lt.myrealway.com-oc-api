<?php namespace Qcsoft\Ocext\Behaviors;

use Backend\Classes\ControllerBehavior;

class MakeSlugController extends ControllerBehavior
{
    public function onMakeSlug()
    {
        $formController = $this->controller->asExtension('FormController');

        $modelClass = class_basename($formController->getConfig('modelClass'));

        $fromField = \Request::input('fromField');

        $fields = \Request::input($modelClass);

        return \Str::slug($fields[$fromField]);
    }

}
