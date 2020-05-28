<?php namespace Ydnnov\Catalog\Models;

use Ydnnov\Catalog\Modelsbase\FilterBase;

class Filter extends FilterBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
