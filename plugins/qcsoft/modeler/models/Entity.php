<?php namespace Qcsoft\Modeler\Models;

use October\Rain\Database\Model;

class Entity extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_apprefl_entity';

    public $hasMany = [
        'options' => [EntityOption::class],
    ];

}
