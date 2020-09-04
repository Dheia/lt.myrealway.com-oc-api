<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;

/**
 * Class OrderstatusBase
 * @package Qcsoft\App\Modelsbase
 * @property int $id
 * @property string $name
 */
class OrderstatusBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_orderstatus';

}
