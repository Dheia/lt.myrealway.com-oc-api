<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Filteroption;
use Qcsoft\App\Models\Product;

/**
 * Class FilteroptionProductBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property int $filteroption_id
 * @property int $product_id
 * @property Filteroption $filteroption;
 * @property Product $product;
 */
class FilteroptionProductBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_filteroption_product';

    public $hasMany = [
    ];

    public $belongsTo = [
        'filteroption' => [Filteroption::class],
        'product' => [Product::class],
    ];

}
