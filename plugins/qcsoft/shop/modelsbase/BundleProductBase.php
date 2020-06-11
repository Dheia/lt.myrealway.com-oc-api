<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Bundle;
use Qcsoft\Shop\Models\Product;

/**
 * Class BundleProductBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $bundle_id
 * @property int $product_id
 * @property int $quantity
 * @property int $sort_order
 * @property int $price_override
 * @property Bundle $bundle;
 * @property Product $product;
 */
class BundleProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_bundle_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'bundle' => [Bundle::class],
        'product' => [Product::class],
    ];

}
