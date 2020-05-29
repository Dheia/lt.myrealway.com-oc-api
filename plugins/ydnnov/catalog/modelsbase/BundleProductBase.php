<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\Bundle;
use Ydnnov\Catalog\Models\Product;

/**
 * Class BundleProductBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property int $bundle_id
 * @property int $product_id
 * @property Bundle $bundle;
 * @property Product $product;
 */
class BundleProductBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_bundle_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'bundle' => [Bundle::class],
        'product' => [Product::class],
    ];

}
