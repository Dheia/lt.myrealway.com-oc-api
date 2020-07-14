<?php namespace Qcsoft\App\Models;

use October\Rain\Database\Traits\NestedTree;
use Qcsoft\App\Modelsbase\CategoryBase;
use System\Models\File;

class Category extends CategoryBase
{
    use \October\Rain\Database\Traits\Validation;
    use NestedTree;

    public $rules = [];

    public $attachOne = [
        'main_image' => [File::class],
    ];

}
