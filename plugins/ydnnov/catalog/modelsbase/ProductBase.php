<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\CategoryProduct;
use Ydnnov\Catalog\Models\BundleProduct;
use Ydnnov\Catalog\Models\FilteroptionProduct;
use Ydnnov\Catalog\Models\Category;

/**
 * Class ProductBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property int $main_category_id
 * @property string $name
 * @property string $description
 */
class ProductBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_product';

    public $hasMany = [
        'product_categories' => [CategoryProduct::class],
        'product_bundles' => [BundleProduct::class],
        'product_filteroptions' => [FilteroptionProduct::class],
    ];

    public $belongsTo = [
        'main_category' => [Category::class],
    ];

}
