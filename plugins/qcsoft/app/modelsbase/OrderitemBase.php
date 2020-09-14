<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;

/**
 * Class OrderitemBase
 * @package Qcsoft\App\Modelsbase
 * @property int $catalogitem_id
 * @property int $id
 * @property int $order_id
 * @property int $quantity
 */
class OrderitemBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_orderitem';

}
