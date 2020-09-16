<?php namespace Qcsoft\App\Models;

use October\Rain\Database\Traits\NestedTree;
use Qcsoft\App\Modelsbase\GenericblockBase;

class Genericblock extends GenericblockBase
{
    use \October\Rain\Database\Traits\Validation;
    use NestedTree;

    public $rules = [];

}
