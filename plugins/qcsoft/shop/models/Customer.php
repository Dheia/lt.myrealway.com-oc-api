<?php namespace Qcsoft\Shop\Models;

use Qcsoft\Shop\Modelsbase\CustomerBase;

class Customer extends CustomerBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
