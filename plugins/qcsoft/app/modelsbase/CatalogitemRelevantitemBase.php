<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Catalogitem;

/**
 * Class CatalogitemRelevantitemBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property Catalogitem $main_catalogitem
 * @property int $main_catalogitem_id
 * @property Catalogitem $relevant_catalogitem
 * @property int $relevant_catalogitem_id
 */
class CatalogitemRelevantitemBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_catalogitem_relevantitem';

    public $belongsTo = [
        'main_catalogitem' => [Catalogitem::class],
        'relevant_catalogitem' => [Catalogitem::class],
    ];

}
