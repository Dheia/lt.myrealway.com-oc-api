<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Cart;
use Qcsoft\App\Models\Customergroup;
use October\Rain\Database\Collection;

/**
 * Class CustomerBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property Collection $carts;
 * @property Customergroup $group;
 */
class CustomerBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_customer';

    public $hasMany = [
        'carts' => [Cart::class],
    ];

    public $belongsTo = [
        'group' => [Customergroup::class],
    ];

}
