<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Cart;
use Qcsoft\Shop\Models\Customergroup;
use October\Rain\Database\Collection;

/**
 * Class CustomerBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property Collection $carts;
 * @property Customergroup $group;
 */
class CustomerBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_customer';

    public $hasMany = [
        'carts' => [Cart::class],
    ];

    public $belongsTo = [
        'group' => [Customergroup::class],
    ];

}
