<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Filteroption;

/**
 * Class FilterBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $filteroptions
 * @property int $id
 * @property boolean $is_in_bundles
 * @property boolean $is_in_products
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 */
class FilterBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_filter';

    public $hasMany = [
        'filteroptions' => [Filteroption::class, 'delete' => true],
    ];

}
