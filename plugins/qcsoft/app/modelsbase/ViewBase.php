<?php namespace Qcsoft\App\Modelsbase;

use October\Rain\Database\Model;

/**
 * Class ViewBase
 * @package Qcsoft\App\Modelsbase
 * @property string $code
 * @property int $id
 * @property string $name
 * @property string $owner_type
 */
class ViewBase extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_app_view';

}
