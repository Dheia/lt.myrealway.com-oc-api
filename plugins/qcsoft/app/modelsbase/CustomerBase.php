<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Cart;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Organization;
use Qcsoft\App\Models\Person;

/**
 * Class CustomerBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $carts
 * @property mixed $entity
 * @property int $entity_id
 * @property string $entity_type
 * @property Customergroup $group
 * @property int $group_id
 * @property int $id
 * @property string $password
 * @property string $salt
 */
class CustomerBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_customer';

    public $belongsTo = [
        'group' => [Customergroup::class],
    ];

    public $morphTo = [
        'entity' => [],
    ];

    public $hasMany = [
        'carts' => [Cart::class, 'delete' => false],
    ];

}
