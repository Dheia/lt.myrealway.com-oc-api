<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Product;
use Qcsoft\App\Models\CategoryProduct;
use October\Rain\Database\Collection;

/**
 * Class CategoryBase
 * @package Qcsoft\App\Modelsbase
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

    public $table = 'qcsoft_app_category';

    public $hasMany = [
        'products' => [Product::class],
        'category_products' => [CategoryProduct::class],
    ];

    public $belongsTo = [
    ];

}
