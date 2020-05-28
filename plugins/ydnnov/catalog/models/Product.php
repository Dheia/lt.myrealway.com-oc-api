<?php namespace Ydnnov\Catalog\Models;

use Ydnnov\Catalog\Modelsbase\ProductBase;

class Product extends ProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
