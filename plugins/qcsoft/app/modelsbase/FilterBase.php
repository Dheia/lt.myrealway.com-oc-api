<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Filteroption;
use October\Rain\Database\Collection;

/**
 * Class FilterBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property Collection $filteroptions;
 */
class FilterBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_filter';

    public $hasMany = [
        'filteroptions' => [Filteroption::class],
    ];

    public $belongsTo = [
    ];

}
