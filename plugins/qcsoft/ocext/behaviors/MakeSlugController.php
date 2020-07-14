<?php namespace Qcsoft\Ocext\Behaviors;

use Backend\Classes\ControllerBehavior;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\Sluggable;

class MakeSlugController extends ControllerBehavior
{
    public function onMakeSlug()
    {
        $formController = $this->controller->asExtension('FormController');

        $modelClass = $formController->getConfig('modelClass');

        $requestFields = \Request::input(class_basename($modelClass));

        $slugField = \Request::input('slugField');

        if ($modelId = \Request::input('recordId'))
        {
            /** @var Model $model */
            $model = $modelClass::find($modelId);
        }
        else
        {
            $model = new $modelClass();
        }

        foreach ($requestFields as $key => $value)
        {
            $model->$key = $value;
        }

        $parts = explode('.', $slugField);

        $last = array_pop($parts);

        /** @var Sluggable $slugModel */
        $slugModel = $model;

        foreach ($parts as $part)
        {
            list($forward, $back) = explode('/', $part);

            if ($slugModel->{$forward})
            {
                $slugModel = $slugModel->{$forward};
            }
            else
            {
                $nextModel = $slugModel->{$forward}()->getRelated();

                $nextModel->$back = $slugModel;

                $slugModel = $nextModel;
            }
        }

        $slugModel->setSluggedValue($last, $slugModel->slugs[$last]);

        return $slugModel->$last;
    }

}
