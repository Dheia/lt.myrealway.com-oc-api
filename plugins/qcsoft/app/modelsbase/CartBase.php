<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Cartitem;
use Qcsoft\App\Models\Customer;

/**
 * Class CartBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $cartitems
 * @property Customer $customer
 * @property int $customer_id
 * @property int $id
 * @property string $session_key
 */
class CartBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_cart';

    public $belongsTo = [
        'customer' => [Customer::class],
    ];

    public $hasMany = [
        'cartitems' => [Cartitem::class, 'delete' => false],
    ];

}
