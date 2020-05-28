<?php namespace Ydnnov\Catalog\Modelsbase;

use October\Rain\Database\Model;
use Ydnnov\Catalog\Models\Filteroption;

/**
 * Class FilterBase
 * @package Ydnnov\Catalog\Modelsbase
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $description
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
