<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CatalogitemCategoryBase;

class CatalogitemCategory extends CatalogitemCategoryBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
