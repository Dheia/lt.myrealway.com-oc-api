<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Cart;

/**
 * Class CartitemBase
 * @package Qcsoft\App\Modelsbase
 * @property Cart $cart
 * @property int $cart_id
 * @property int $id
 * @property int $quantity
 * @property int $sellable_id
 * @property string $sellable_type
 */
class CartitemBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_cartitem';

    public $belongsTo = [
        'cart' => [Cart::class],
    ];

}
