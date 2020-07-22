<?php namespace Qcsoft\App\Traits;

use October\Rain\Database\Model;

/**
 * Trait RelationWidgetModel
 * @package Qcsoft\App\Traits
 * @mixin Model
 */
trait RelationWidgetModel
{
    public static function bootCompositeModel()
    {
        static::extend(function (Model $model)
        {
            $saveAttributes = [];

            $model->bindEvent('model.saveInternal', function () use ($model, &$saveAttributes)
            {

            });

            $model->bindEvent('model.afterSave', function () use ($model, &$saveAttributes)
            {

            });
        });
    }

}
