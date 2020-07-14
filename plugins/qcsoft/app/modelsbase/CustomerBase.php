<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Cart;
use Qcsoft\App\Models\Customergroup;

/**
 * Class CustomerBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $carts
 * @property Customergroup $group
 * @property int $group_id
 * @property int $id
 * @property int $user_id
 */
class CustomerBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_customer';

    public $belongsTo = [
        'group' => [Customergroup::class],
    ];

    public $hasMany = [
        'carts' => [Cart::class, 'delete' => false],
    ];

}
