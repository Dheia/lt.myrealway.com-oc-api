<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Cartitem;
use Qcsoft\App\Models\Customer;
use October\Rain\Database\Collection;

/**
 * Class CartBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property string $session_key
 * @property int $customer_id
 * @property Collection $cartitems;
 * @property Customer $customer;
 */
class CartBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_cart';

    public $hasMany = [
        'cartitems' => [Cartitem::class],
    ];

    public $belongsTo = [
        'customer' => [Customer::class],
    ];

}
