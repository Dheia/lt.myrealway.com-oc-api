<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Filteroption;

/**
 * Class CatalogitemFilteroptionBase
 * @package Qcsoft\App\Modelsbase
 * @property Catalogitem $catalogitem
 * @property int $catalogitem_id
 * @property Filteroption $filteroption
 * @property int $filteroption_id
 * @property int $id
 */
class CatalogitemFilteroptionBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_catalogitem_filteroption';

    public $belongsTo = [
        'filteroption' => [Filteroption::class],
        'catalogitem' => [Catalogitem::class],
    ];

}
