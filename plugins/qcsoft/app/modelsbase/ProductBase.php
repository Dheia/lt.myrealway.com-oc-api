<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\CategoryProduct;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\FilteroptionProduct;
use Qcsoft\App\Models\CustomergroupProduct;
use Qcsoft\App\Models\Category;
use October\Rain\Database\Collection;

/**
 * Class ProductBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property int $main_category_id
 * @property string $name
 * @property string $path
 * @property string $custom_h1_title
 * @property string $custom_seo_title
 * @property string $seo_desc
 * @property string $description
 * @property int $default_price
 * @property Collection $product_categories;
 * @property Collection $product_bundles;
 * @property Collection $product_filteroptions;
 * @property Collection $product_customergroups;
 * @property Category $main_category;
 */
class ProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_product';

    public $hasMany = [
        'product_categories' => [CategoryProduct::class],
        'product_bundles' => [BundleProduct::class],
        'product_filteroptions' => [FilteroptionProduct::class],
        'product_customergroups' => [CustomergroupProduct::class],
    ];

    public $belongsTo = [
        'main_category' => [Category::class],
    ];

}
