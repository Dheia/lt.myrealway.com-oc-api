<?php namespace Qcsoft\App\Models;

use October\Rain\Database\Collection;
use Qcsoft\App\Modelsbase\LayoutBase;

class Layout extends LayoutBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected static $cached;

    public static function cached(): Collection
    {
        if (!static::$cached)
        {
            static::$cached = static::all();
        }

        return static::$cached;
    }

}
