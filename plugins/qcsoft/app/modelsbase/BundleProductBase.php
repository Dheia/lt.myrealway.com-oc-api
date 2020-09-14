<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProductCustomergroup;
use Qcsoft\App\Models\Product;

/**
 * Class BundleProductBase
 * @package Qcsoft\App\Modelsbase
 * @property Bundle $bundle
 * @property int $bundle_id
 * @property Collection $bundle_product_customergroups
 * @property int $id
 * @property int $price_override
 * @property Product $product
 * @property int $product_id
 * @property int $quantity
 * @property int $sort_order
 */
class BundleProductBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_bundle_product';

    public $belongsTo = [
        'bundle' => [Bundle::class],
        'product' => [Product::class],
    ];

    public $hasMany = [
        'bundle_product_customergroups' => [BundleProductCustomergroup::class, 'delete' => false],
    ];

}
