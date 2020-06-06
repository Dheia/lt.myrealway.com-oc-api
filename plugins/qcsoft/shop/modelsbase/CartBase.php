<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Cartitem;
use Qcsoft\Shop\Models\Customer;
use October\Rain\Database\Collection;

/**
 * Class CartBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property string $session_key
 * @property int $customer_id
 * @property Collection $cartitems;
 * @property Customer $customer;
 */
class CartBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_cart';

    public $hasMany = [
        'cartitems' => [Cartitem::class],
    ];

    public $belongsTo = [
        'customer' => [Customer::class],
    ];

}
