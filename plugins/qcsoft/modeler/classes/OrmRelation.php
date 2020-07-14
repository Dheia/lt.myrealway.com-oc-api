<?php namespace Qcsoft\Modeler\Classes;

class OrmRelation
{
    /** @var OrmField */
    public $fromField;

    /** @var OrmField */
    public $toField;

    /** @var object */
    public $relation;

    /**
     * OrmRelation constructor.
     * @param OrmField $fromField
     * @param OrmField $toField
     * @param object $relation
     */
    public function __construct(OrmField $fromField, OrmField $toField, object $relation)
    {
        $this->fromField = $fromField;
        $this->toField = $toField;
        $this->relation = $relation;
    }

    public function keyBelongsTo()
    {
        return preg_replace('/(.+)_id$/', '$1', $this->fromField->attribute->name);
    }

    public function keyHasOne()
    {
        $fromName = $this->fromField->model->entity->name;

        if (strpos($fromName, '_') === false)
        {
            return $fromName;
        }
        else
        {
            $toName = $this->toField->model->entity->name;

            $endingPart = trim(str_replace(
                $toName, '', $fromName
            ), '_');

            return $toName . '_' . $endingPart;
        }
    }

    public function keyHasMany()
    {
        $fromName = $this->fromField->model->entity->name;

        if (strpos($fromName, '_') === false)
        {
            return \Str::plural($fromName);
        }
        else
        {
            $toName = $this->toField->model->entity->name;

            $pluralizedPart = trim(str_replace(
                $toName, '', $fromName
            ), '_');

            return $toName . '_' . \Str::plural($pluralizedPart);
        }
    }

}
