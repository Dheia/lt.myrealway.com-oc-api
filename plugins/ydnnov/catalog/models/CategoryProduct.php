<?php namespace Ydnnov\Catalog\Models;

use Ydnnov\Catalog\Modelsbase\CategoryProductBase;

class CategoryProduct extends CategoryProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
