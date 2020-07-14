<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\BundleProductCustomergroup;
use Qcsoft\App\Models\CatalogitemCustomergroup;
use Qcsoft\App\Models\Customer;

/**
 * Class CustomergroupBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $customergroup_bundle_products
 * @property Collection $customergroup_catalogitems
 * @property Collection $customers
 * @property int $discount_percent
 * @property int $id
 * @property boolean $is_default
 * @property string $name
 */
class CustomergroupBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_customergroup';

    public $hasMany = [
        'customergroup_bundle_products' => [BundleProductCustomergroup::class, 'delete' => false],
        'customers' => [Customer::class, 'delete' => false],
        'customergroup_catalogitems' => [CatalogitemCustomergroup::class, 'delete' => false],
    ];

}
