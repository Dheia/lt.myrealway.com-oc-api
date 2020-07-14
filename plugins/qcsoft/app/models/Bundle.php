<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\BundleBase;
use System\Models\File;

class Bundle extends BundleBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected static function boot()
    {
        parent::boot();

        static::extend(function ($model)
        {
            /** @var static $model */

            ////////////////////////////////////////////////////////////////////////////////
            /// Auto delete related
            ////////////////////////////////////////////////////////////////////////////////
            $model->hasMany['bundle_products']['delete'] = true;
        });
    }

    public function getNameAttribute()
    {
        return $this->catalogitem_name;
    }

    public function setNameAttribute($value)
    {
        return $this->catalogitem_name = $value;
    }

    public function getDefaultPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setDefaultPriceAttribute($value)
    {
        return $this->attributes['default_price'] = $value * 100;
    }

}
