<?php namespace Qcsoft\App\Models;

use October\Rain\Database\Model;

class Entity extends Model
{
    public $timestamps = false;

    public $table = 'qcsoft_apprefl_entity';

    public $hasMany = [
        'options' => [EntityOption::class],
    ];

    public static $all;

    public static function loadAll()
    {
        static::$all = static::all();
    }

    public static function idByType($type)
    {
        return static::$all->firstWhere('name', $type)->id;
    }

    public static function idByClassname($classname)
    {
        return static::$all->firstWhere('classname', $classname)->id;
    }

    public static function typeById($id)
    {
        return static::$all->firstWhere('id', $id)->name;
    }

    public static function typeByClassname($classname)
    {
        return static::$all->firstWhere('classname', $classname)->name;
    }

    public static function classnameByType($type)
    {
        return static::$all->firstWhere('name', $type)->classname;
    }

    public static function classnameById($id)
    {
        return static::$all->firstWhere('id', $id)->classname;
    }

    public function getClassnameAttribute()
    {
        return __NAMESPACE__ . '\\' . \Str::studly($this->name);
    }

}
