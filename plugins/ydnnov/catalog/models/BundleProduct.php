<?php namespace Ydnnov\Catalog\Models;

use Ydnnov\Catalog\Modelsbase\BundleProductBase;

class BundleProduct extends BundleProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
