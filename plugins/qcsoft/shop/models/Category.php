<?php namespace Qcsoft\Shop\Models;

use October\Rain\Database\Traits\NestedTree;
use Qcsoft\Cms\Classes\PageModel;
use Qcsoft\Shop\Modelsbase\CategoryBase;
use System\Models\File;

class Category extends CategoryBase
{
    use \October\Rain\Database\Traits\Validation;
    use NestedTree;
    use PageModel;

    public $rules = [];

    public $attachOne = [
        'main_image' => [File::class],
    ];

}
