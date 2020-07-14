<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Customergroup;

/**
 * Class CatalogitemCustomergroupBase
 * @package Qcsoft\App\Modelsbase
 * @property Catalogitem $catalogitem
 * @property int $catalogitem_id
 * @property Customergroup $customergroup
 * @property int $customergroup_id
 * @property int $id
 * @property int $price
 */
class CatalogitemCustomergroupBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_catalogitem_customergroup';

    public $belongsTo = [
        'customergroup' => [Customergroup::class],
        'catalogitem' => [Catalogitem::class],
    ];

}
