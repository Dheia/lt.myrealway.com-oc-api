<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;

/**
 * Class LayoutBase
 * @package Qcsoft\App\Modelsbase
 * @property string $code
 * @property int $id
 * @property string $name
 * @property int $owner_type_id
 */
class LayoutBase extends Model
{
    public static $type_id;

    public $timestamps = false;

    public $table = 'qcsoft_app_layout';

}
