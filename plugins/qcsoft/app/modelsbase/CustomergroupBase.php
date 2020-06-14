<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Customer;
use Qcsoft\App\Models\CustomergroupProduct;
use Qcsoft\App\Models\BundleProductCustomergroup;
use October\Rain\Database\Collection;

/**
 * Class CustomergroupBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property string $name
 * @property boolean $is_default
 * @property int $discount_percent
 * @property Collection $customers;
 * @property Collection $customergroup_products;
 * @property Collection $customergroup_bundle_products;
 */
class CustomergroupBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_customergroup';

    public $hasMany = [
        'customers' => [Customer::class],
        'customergroup_products' => [CustomergroupProduct::class],
        'customergroup_bundle_products' => [BundleProductCustomergroup::class],
    ];

    public $belongsTo = [
    ];

}
