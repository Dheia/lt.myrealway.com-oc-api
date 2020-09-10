<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\Filter;

/**
 * Class FilteroptionBase
 * @package Qcsoft\App\Modelsbase
 * @property Filter $filter
 * @property int $filter_id
 * @property Collection $filteroption_catalogitems
 * @property int $id
 * @property boolean $is_in_bundles
 * @property boolean $is_in_products
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 */
class FilteroptionBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_filteroption';

    public $belongsTo = [
        'filter' => [Filter::class],
    ];

    public $hasMany = [
        'filteroption_catalogitems' => [CatalogitemFilteroption::class, 'delete' => false],
    ];

}
