<?php namespace Ydnnov\Crudgen\Classes;

use Illuminate\Support\Collection;
use RainLab\Builder\Classes\PluginCode;

class DbmlModel
{
    /** @var PluginCode */
    public $pluginCodeObj;

    /** @var string */
    public $tableName;

    /** @var Collection */
    public $hasMany;

    /** @var Collection */
    public $belongsTo;

    public $columns = [];

    /**
     * @param PluginCode $pluginCodeObj
     * @param string $tableName
     */
    public function __construct($pluginCodeObj, $tableName)
    {
        $this->pluginCodeObj = $pluginCodeObj;
        $this->tableName = $tableName;
        $this->hasMany = new Collection();
        $this->belongsTo = new Collection();
    }

    public function getQualifiedTableName()
    {
        return $this->pluginCodeObj->toDatabasePrefix() . '_' . $this->tableName;
    }

    public function getNamespace()
    {
        return $this->pluginCodeObj->toPluginNamespace() . '\\Models';
    }

    public function getClassname()
    {
        return \Str::studly($this->tableName);
    }

    public function getQualifiedBaseClassname()
    {
        return $this->getNamespace() . 'base\\' . $this->getClassname() . 'Base';
    }

    public function getQualifiedClassname()
    {
        return $this->getNamespace() . '\\' . $this->getClassname();
    }

}
