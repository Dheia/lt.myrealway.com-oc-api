<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\FilteroptionBase;

class Filteroption extends FilteroptionBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected static function boot()
    {
        parent::boot();

//        static::extend(function ($model) {
//
//            /** @var static $model */
//
//            ////////////////////////////////////////////////////////////////////////////////
//            /// Auto delete related
//            ////////////////////////////////////////////////////////////////////////////////
//            $model->hasMany['filteroption_products']['delete'] = true;
//        });
    }

    public function getSlugAttribute()
    {
        return \Str::slug($this->name);
    }

}
