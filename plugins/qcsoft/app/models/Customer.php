<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CustomerBase;

class Customer extends CustomerBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
