<?php namespace Qcsoft\Cms\Models;

use Qcsoft\Cms\Classes\PageModel;
use Qcsoft\Cms\Modelsbase\GenericpageBase;

class Genericpage extends GenericpageBase
{
    use PageModel;

    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
