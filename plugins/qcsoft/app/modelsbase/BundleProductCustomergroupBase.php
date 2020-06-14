<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Customergroup;

/**
 * Class BundleProductCustomergroupBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property int $bundle_product_id
 * @property int $customergroup_id
 * @property string $discount_value_type
 * @property int $discount_value
 * @property BundleProduct $bundle_product;
 * @property Customergroup $customergroup;
 */
class BundleProductCustomergroupBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_bundle_product_customergroup';

    public $hasMany = [
    ];

    public $belongsTo = [
        'bundle_product' => [BundleProduct::class],
        'customergroup' => [Customergroup::class],
    ];

}
