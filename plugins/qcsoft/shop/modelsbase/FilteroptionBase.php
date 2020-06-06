<?php namespace Qcsoft\Shop\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\Shop\Models\FilteroptionProduct;
use Qcsoft\Shop\Models\Filter;
use October\Rain\Database\Collection;

/**
 * Class FilteroptionBase
 * @package Qcsoft\Shop\Modelsbase
 * @property int $id
 * @property int $filter_id
 * @property string $name
 * @property string $description
 * @property int $sort_order
 * @property Collection $filteroption_products;
 * @property Filter $filter;
 */
class FilteroptionBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_shop_filteroption';

    public $hasMany = [
        'filteroption_products' => [FilteroptionProduct::class],
    ];

    public $belongsTo = [
        'filter' => [Filter::class],
    ];

}
