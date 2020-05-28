<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\Filteroption;
use Ydnnov\Catalog\Models\Product;

/**
 * Class FilteroptionProductBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property int $filteroption_id
 * @property int $product_id
 */
class FilteroptionProductBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_filteroption_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'filteroption' => [Filteroption::class],
        'product' => [Product::class],
    ];

}
