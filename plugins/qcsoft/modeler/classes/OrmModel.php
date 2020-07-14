<?php namespace Qcsoft\Modeler\Classes;

use Illuminate\Support\Collection;
use RainLab\Builder\Classes\PluginCode;

class OrmModel
{
    /** @var OrmSchema */
    public $schema;

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
    public $traits = [];

    /** @var array */
    public $extraProps = [];

    /** @var array */
    public $extraPropsMulti = [];

    /** @var array */
    public $relationDecls = [];

    /** @var array */
    public $namePartsPl;

    /**
     * OrmModel constructor.
     * @param OrmSchema $schema
     * @param object $entity
     */
    public function __construct(OrmSchema $schema, object $entity)
    {
        $this->schema = $schema;
        $this->entity = $entity;

        $this->fields = collect();
        $this->relationsFrom = collect();
        $this->relationsTo = collect();

        $this->namePartsPl = array_map(['\Str', 'plural'], explode('_', $this->entity->name));
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

    public function addTrait(string $name)
    {
        if (!in_array($name, $this->traits))
        {
            $this->traits[] = $name;
        }

        return $this;
    }

    public function setExtraProp(string $name, $value)
    {
        $this->extraProps[$name] = $value;

        return $this;
    }

    public function setExtraPropMulti(string $name, $key, $value)
    {
        $this->extraPropsMulti[$name] = array_get($this->extraPropsMulti, $name, []);

        $this->extraPropsMulti[$name][$key] = $value;

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
        return $this->schema->plugin->toDatabasePrefix() . '_' . $this->entity->name;
    }

    public function getNamespace()
    {
        return $this->schema->plugin->toPluginNamespace() . '\\Models';
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

    public function getModelDirPath()
    {
        return plugins_path($this->schema->plugin->toFilesystemPath()) . '/models/' .
            strtolower($this->getClassname());
    }

    public function createModelDir()
    {
        if (!is_dir($modeldir = $this->getModelDirPath()))
        {
            mkdir($modeldir);

//            \Debugbar::info("mkdir($modeldir)");
        }

        return $modeldir;
    }

    public function createModelFile($filename)
    {
        $modeldir = $this->createModelDir();

        $filepath = "$modeldir/$filename";

        return file_exists($filepath) ? false : $filepath;
    }

    public function getControllerClassname()
    {
        return implode('', array_map(['\Str', 'ucfirst'], $this->namePartsPl));
    }

    public function getControllerQualifiedClassname()
    {
        return $this->schema->plugin->toPluginNamespace() .
            '\\Controllers\\' .
            $this->getControllerClassname();
    }

    public function getControllerPhpFilepath()
    {
        $filepath = plugins_path($this->schema->plugin->toFilesystemPath())
            . '/controllers/' .
            $this->getControllerClassname() . '.php';

        return $filepath;
    }

    public function getControllerUrl()
    {
        return $this->schema->plugin->toUrl() . '/' . implode('', $this->namePartsPl);
    }

    public function getSideMenuKey()
    {
        return 'side-menu-' . implode('-', $this->namePartsPl);
    }

    public function getDefaultSideMenuItem()
    {
        return [
            'label' => implode(' ', array_map(['\Str', 'ucfirst'], $this->namePartsPl)),
            'url'   => $this->getControllerUrl(),
            'icon'  => 'icon-sitemap',
        ];
    }

}
