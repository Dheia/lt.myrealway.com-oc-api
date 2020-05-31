<?php namespace Ydnnov\Catalog\Models;

use Ydnnov\Catalog\Modelsbase\FilteroptionBase;

class Filteroption extends FilteroptionBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected static function boot()
    {
        parent::boot();

        static::extend(function ($model) {

            /** @var Filteroption $model */

            ////////////////////////////////////////////////////////////////////////////////
            /// Auto delete related
            ////////////////////////////////////////////////////////////////////////////////
            $model->hasMany['filteroption_products']['delete'] = true;
        });
    }

}
