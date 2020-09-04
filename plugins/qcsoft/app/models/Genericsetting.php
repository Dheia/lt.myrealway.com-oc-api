<?php namespace Qcsoft\App\Models;

use October\Rain\Database\Model;

class Genericsetting extends Model
{
    protected $table = 'qcsoft_app_genericsetting';

    public $timestamps = false;

    protected $jsonable = ['value'];
}
