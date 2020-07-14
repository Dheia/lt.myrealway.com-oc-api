<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Collection;
use October\Rain\Database\Model;
use Qcsoft\App\Models\Filteroption;

/**
 * Class FilterBase
 * @package Qcsoft\App\Modelsbase
 * @property Collection $filteroptions
 * @property int $id
 * @property string $name
 */
class FilterBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_filter';

    public $hasMany = [
        'filteroptions' => [Filteroption::class, 'delete' => true],
    ];

}
