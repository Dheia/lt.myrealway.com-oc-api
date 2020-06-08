<?php namespace Qcsoft\Cms\Models;

use October\Rain\Database\Traits\SimpleTree;
use Qcsoft\Cms\Modelsbase\GenericblockBase;

class Genericblock extends GenericblockBase
{
    use SimpleTree;

    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
