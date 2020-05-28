<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\Category;
use Ydnnov\Catalog\Models\Product;

/**
 * Class CategoryProductBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 */
class CategoryProductBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_category_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'category' => [Category::class],
        'product' => [Product::class],
    ];

}
