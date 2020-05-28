<?php namespace Ydnnov\Catalog\Models;

use October\Rain\Database\Traits\NestedTree;
use System\Models\File;
use Ydnnov\Catalog\Modelsbase\CategoryBase;

class Category extends CategoryBase
{
    use \October\Rain\Database\Traits\Validation;
    use NestedTree;

    public $rules = [];

    public $attachOne = [
        'main_image' => [File::class],
    ];

}
