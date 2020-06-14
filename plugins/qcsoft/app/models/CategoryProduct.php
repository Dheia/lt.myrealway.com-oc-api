<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CategoryProductBase;

class CategoryProduct extends CategoryProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
