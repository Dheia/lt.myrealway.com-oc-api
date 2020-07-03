<?php namespace Qcsoft\Modeler\Classes;

use Illuminate\Support\Collection;
use RainLab\Builder\Classes\PluginCode;

class OrmModel
{
    /** @var OrmGenerator */
    public $generator;

    /** @var object */
    public $entity;

    /** @var Collection */
    public $fields;

    /** @var Collection */
    public $relationsFrom;

    /** @var Collection */
    public $relationsTo;

    /** @var array */
    public $classUse = [];

    /** @var array */
    public $properties = [];

    /** @var array */
    public $relationDecls = [];

    /**
     * OrmModel constructor.
     * @param OrmGenerator $generator
     * @param object $entity
     */
    public function __construct(OrmGenerator $generator, object $entity)
    {
        $this->generator = $generator;
        $this->entity = $entity;

        $this->fields = collect();
        $this->relationsFrom = collect();
        $this->relationsTo = collect();
    }

    public function addClassUse(string $classname)
    {
        if (!in_array($classname, $this->classUse))
        {
            $this->classUse[] = $classname;
        }

        return $this;
    }

    public function addProperty(string $type, string $name)
    {
        $this->properties[$name] = $type;

        return $this;
    }

    public function addRelationDecl(string $type, string $name, $definition)
    {
        if (!array_get($this->relationDecls, $type))
        {
            $this->relationDecls[$type] = [];
        };

        $this->relationDecls[$type][$name] = $definition;

        return $this;
    }

    public function getQualifiedTableName()
    {
        return $this->generator->pluginCodeObj->toDatabasePrefix() . '_' . $this->entity->name;
    }

    public function getNamespace()
    {
        return $this->generator->pluginCodeObj->toPluginNamespace() . '\\Models';
    }

    public function getClassname()
    {
        return \Str::studly($this->entity->name);
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
