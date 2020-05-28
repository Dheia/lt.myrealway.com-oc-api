<?php namespace Ydnnov\Catalog\Models;

use Ydnnov\Catalog\Modelsbase\CategoryBase;

class Category extends CategoryBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
