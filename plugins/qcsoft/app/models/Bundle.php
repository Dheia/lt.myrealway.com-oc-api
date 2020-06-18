<?php namespace Qcsoft\App\Models;

use Qcsoft\Cms\Classes\PageModel;
use Qcsoft\App\Modelsbase\BundleBase;
use System\Models\File;

class Bundle extends BundleBase
{
    use PageModel;

    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public $attachOne = [
        'main_image' => [File::class],
    ];

    protected static function boot()
    {
        parent::boot();

        static::extend(function ($model) {

            /** @var static $model */

            ////////////////////////////////////////////////////////////////////////////////
            /// Auto delete related
            ////////////////////////////////////////////////////////////////////////////////
            $model->hasMany['bundle_products']['delete'] = true;
        });
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
