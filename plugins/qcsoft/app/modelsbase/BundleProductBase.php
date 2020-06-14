<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\BundleProductCustomergroup;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Product;
use October\Rain\Database\Collection;

/**
 * Class BundleProductBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property int $bundle_id
 * @property int $product_id
 * @property int $quantity
 * @property int $sort_order
 * @property int $price_override
 * @property Collection $bundle_product_customergroups;
 * @property Bundle $bundle;
 * @property Product $product;
 */
class BundleProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_bundle_product';

    public $hasMany = [
        'bundle_product_customergroups' => [BundleProductCustomergroup::class],
    ];

    public $belongsTo = [
        'bundle' => [Bundle::class],
        'product' => [Product::class],
    ];

}
