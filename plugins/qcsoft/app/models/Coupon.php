<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CouponBase;

class Coupon extends CouponBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
