<?php namespace Qcsoft\Modeler\Classes;

class OrmField
{
    /** @var OrmModel */
    public $model;

    /** @var object */
    public $attribute;

    /**
     * OrmField constructor.
     * @param OrmModel $model
     * @param object $attribute
     */
    public function __construct(OrmModel $model, object $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

}
