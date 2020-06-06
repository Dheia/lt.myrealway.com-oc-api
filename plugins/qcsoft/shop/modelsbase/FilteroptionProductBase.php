<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\Filteroption;
use Qcsoft\Shop\Models\Product;

/**
 * Class FilteroptionProductBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $filteroption_id
 * @property int $product_id
 * @property Filteroption $filteroption;
 * @property Product $product;
 */
class FilteroptionProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_filteroption_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'filteroption' => [Filteroption::class],
        'product' => [Product::class],
    ];

}
