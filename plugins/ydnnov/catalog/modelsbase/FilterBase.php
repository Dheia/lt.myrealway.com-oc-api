<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\Filteroption;
use October\Rain\Database\Collection;

/**
 * Class FilterBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property Collection $filteroptions;
 */
class FilterBase extends Model
{
    public $timestamps = false;

    public $table = 'ydnnov_catalog_filter';

    public $hasMany = [
        'filteroptions' => [Filteroption::class],
    ];

    public $belongsTo = [
    ];

}
