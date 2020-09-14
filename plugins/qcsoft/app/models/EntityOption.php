<?php namespace Qcsoft\App\Models;

use October\Rain\Database\Model;

class EntityOption extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_apprefl_entity_option';

    protected $jsonable = ['value'];

}
