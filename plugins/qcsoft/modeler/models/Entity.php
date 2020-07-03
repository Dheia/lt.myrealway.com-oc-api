<?php namespace Qcsoft\Modeler\Models;

use October\Rain\Database\Model;

class Entity extends Model
{
    public $timestamps = false;

    public $table = 's_e';

    public $hasMany = [
        'options' => [EntityOption::class],
    ];

}
