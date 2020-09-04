<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\OrderBase;

class Order extends OrderBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
