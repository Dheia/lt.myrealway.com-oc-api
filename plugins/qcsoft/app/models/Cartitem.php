<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CartitemBase;

class Cartitem extends CartitemBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected $fillable = ['cart_id', 'sellable_type', 'sellable_id'];

    protected static function boot()
    {
        parent::boot();

        static::extend(function ($model) {

            /** @var static $model */

            ////////////////////////////////////////////////////////////////////////////////
            /// Sellable
            ////////////////////////////////////////////////////////////////////////////////
            $model->morphTo['sellable'] = [];
        });
    }

}
