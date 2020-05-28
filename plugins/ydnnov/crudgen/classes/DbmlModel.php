<?php namespace Ydnnov\Crudgen\Classes;

use RainLab\Builder\Classes\PluginCode;

class DbmlModel
{
    public $pluginCodeObj;

    public $tableName;

    public $hasMany = [];

    public $belongsTo = [];

    public $columns = [];

    /**
     * @param PluginCode $pluginCodeObj
     * @param string $tableName
     */
    public function __construct($pluginCodeObj, $tableName)
    {
        $this->pluginCodeObj = $pluginCodeObj;
        $this->tableName = $tableName;
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
