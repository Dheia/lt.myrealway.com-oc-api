<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\BundleProductBase;

class BundleProduct extends BundleProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public function getPriceOverrideAttribute($value)
    {
        return $value ? $value / 100 : '';
    }

    public function setPriceOverrideAttribute($value)
    {
        return $this->attributes['price_override'] = (float)$value * 100;
    }

}
