<?php namespace Qcsoft\Shop\Models;

use Qcsoft\Shop\Modelsbase\BundleProductBase;

class BundleProduct extends BundleProductBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

}
