<?php namespace Ydnnov\Catalog\Models;

use System\Models\File;
use Ydnnov\Catalog\Modelsbase\ProductBase;

class Product extends ProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public $attachOne = [
        'main_image' => [File::class],
    ];

}
