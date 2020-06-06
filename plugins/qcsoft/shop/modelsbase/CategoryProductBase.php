<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Category;
use Qcsoft\Shop\Models\Product;

/**
 * Class CategoryProductBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property Category $category;
 * @property Product $product;
 */
class CategoryProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_category_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'category' => [Category::class],
        'product' => [Product::class],
    ];

}
