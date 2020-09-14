<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Customergroup;

/**
 * Class BundleProductCustomergroupBase
 * @package Qcsoft\App\Modelsbase
 * @property BundleProduct $bundle_product
 * @property int $bundle_product_id
 * @property Customergroup $customergroup
 * @property int $customergroup_id
 * @property int $discount_value
 * @property string $discount_value_type
 * @property int $id
 */
class BundleProductCustomergroupBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_bundle_product_customergroup';

    public $belongsTo = [
        'bundle_product' => [BundleProduct::class],
        'customergroup' => [Customergroup::class],
    ];

}
