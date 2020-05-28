<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\Product;
use Ydnnov\Catalog\Models\CategoryProduct;

/**
 * Class CategoryBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property int $parent_id
 * @property int $nest_left
 * @property int $nest_right
 * @property int $nest_depth
 * @property string $name
 * @property string $description
 */
class CategoryBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_category';

    public $hasMany = [
        'products' => [Product::class],
        'category_products' => [CategoryProduct::class],
    ];

    public $belongsTo = [
    ];

}
