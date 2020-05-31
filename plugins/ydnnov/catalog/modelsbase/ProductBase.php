<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\CategoryProduct;
use Ydnnov\Catalog\Models\BundleProduct;
use Ydnnov\Catalog\Models\FilteroptionProduct;
use Ydnnov\Catalog\Models\Category;
use October\Rain\Database\Collection;

/**
 * Class ProductBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property int $main_category_id
 * @property string $path
 * @property string $h1_title
 * @property string $seo_title
 * @property string $seo_desc
 * @property string $name
 * @property string $description
 * @property Collection $product_categories;
 * @property Collection $product_bundles;
 * @property Collection $product_filteroptions;
 * @property Category $main_category;
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
