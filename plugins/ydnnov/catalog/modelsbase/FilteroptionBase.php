<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\FilteroptionProduct;
use Ydnnov\Catalog\Models\Filter;

/**
 * Class FilteroptionBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property int $filter_id
 * @property string $name
 * @property string $description
 * @property int $sort_order
 */
class FilteroptionBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_filteroption';

    public $hasMany = [
        'filteroption_products' => [FilteroptionProduct::class],
    ];

    public $belongsTo = [
        'filter' => [Filter::class],
    ];

}
