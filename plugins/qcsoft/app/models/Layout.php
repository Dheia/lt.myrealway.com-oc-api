<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\LayoutBase;

class Layout extends LayoutBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected static $cached;

    public static function cached()
    {
        if (!static::$cached)
        {
            static::$cached = static::all();
        }

        return static::$cached;
    }

}
