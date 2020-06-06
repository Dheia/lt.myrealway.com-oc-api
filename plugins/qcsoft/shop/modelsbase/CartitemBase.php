<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Cart;

/**
 * Class CartitemBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $cart_id
 * @property string $sellable_type
 * @property int $sellable_id
 * @property int $quantity
 * @property Cart $cart;
 */
class CartitemBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_cartitem';

    public $hasMany = [
    ];

    public $belongsTo = [
        'cart' => [Cart::class],
    ];

}
