<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Product;

/**
 * Class CategoryProductBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property Category $category;
 * @property Product $product;
 */
class CategoryProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_category_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'category' => [Category::class],
        'product' => [Product::class],
    ];

}
