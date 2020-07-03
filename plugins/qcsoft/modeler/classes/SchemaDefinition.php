<?php namespace Qcsoft\Modeler\Classes;

use Illuminate\Support\Collection;

class SchemaDefinition
{
    /** @var Collection */
    public $entities;

    /** @var Collection */
    public $attributes;

    /** @var Collection */
    public $relations;

    /**
     * SchemaDefinition constructor.
     * @param Collection $entities
     * @param Collection $attributes
     * @param Collection $relations
     */
    public function __construct(Collection $entities, Collection $attributes, Collection $relations)
    {
        $this->entities = $entities;
        $this->attributes = $attributes;
        $this->relations = $relations;
    }

}
