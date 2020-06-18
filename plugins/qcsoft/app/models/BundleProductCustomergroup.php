<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\BundleProductCustomergroupBase;

class BundleProductCustomergroup extends BundleProductCustomergroupBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public function getDiscountValueAttribute($value)
    {
        return $value ? $value / 100 : '';
    }

    public function setDiscountValueAttribute($value)
    {
        return $this->attributes['discount_value'] = (float)$value * 100;
    }

}
