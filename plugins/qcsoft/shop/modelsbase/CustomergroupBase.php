<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Customer;
use Qcsoft\Shop\Models\CustomergroupProduct;
use October\Rain\Database\Collection;

/**
 * Class CustomergroupBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property string $name
 * @property Collection $customers;
 * @property Collection $customergroup_products;
 */
class CustomergroupBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_customergroup';

    public $hasMany = [
        'customers' => [Customer::class],
        'customergroup_products' => [CustomergroupProduct::class],
    ];

    public $belongsTo = [
    ];

}
