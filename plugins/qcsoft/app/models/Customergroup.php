<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CustomergroupBase;

class Customergroup extends CustomergroupBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public function getDiscountPercentAttribute($value)
    {
        return $value / 100;
    }

    public function setDiscountPercentAttribute($value)
    {
        return $this->attributes['discount_percent'] = $value * 100;
    }

}
