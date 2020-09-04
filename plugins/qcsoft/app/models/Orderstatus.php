<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\OrderstatusBase;

class Orderstatus extends OrderstatusBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
