<?php namespace Qcsoft\Modeler\Models;

use October\Rain\Database\Model;

class EntityOption extends Model
{
    public $timestamps = false;

    public $table = 's_e_opt';

    protected $jsonable = ['value'];

}
