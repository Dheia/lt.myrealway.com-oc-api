<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\OrderitemBase;

class Orderitem extends OrderitemBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
