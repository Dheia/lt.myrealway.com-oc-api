<?php namespace Qcsoft\Shop\Models;

use Qcsoft\Shop\Modelsbase\CategoryProductBase;

class CategoryProduct extends CategoryProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
