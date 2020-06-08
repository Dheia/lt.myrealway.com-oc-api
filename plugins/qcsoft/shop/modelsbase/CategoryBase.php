<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Product;
use Qcsoft\Shop\Models\CategoryProduct;
use October\Rain\Database\Collection;

/**
 * Class CategoryBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $parent_id
 * @property int $nest_left
 * @property int $nest_right
 * @property int $nest_depth
 * @property string $name
 * @property string $path
 * @property string $custom_h1_title
 * @property string $custom_seo_title
 * @property string $seo_desc
 * @property string $description
 * @property Collection $products;
 * @property Collection $category_products;
 */
class CategoryBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_category';

    public $hasMany = [
        'products' => [Product::class],
        'category_products' => [CategoryProduct::class],
    ];

    public $belongsTo = [
    ];

}
