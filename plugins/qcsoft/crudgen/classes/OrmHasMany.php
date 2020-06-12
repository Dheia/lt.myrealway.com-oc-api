<?php namespace Qcsoft\Crudgen\Classes;

class OrmHasMany
{
    /** @var OrmModel */
    public $model;

    /** @var string */
    public $modelKey;

    /** @var OrmModel */
    public $relatedModel;

    /** @var string */
    public $relatedForeignKey;

    /**
     * OrmHasMany constructor.
     * @param OrmModel $model
     * @param string $modelKey
     * @param OrmModel $relatedModel
     * @param string $relatedForeignKey
     */
    public function __construct(OrmModel $model, string $modelKey, OrmModel $relatedModel, string $relatedForeignKey)
    {
        $this->model = $model;
        $this->modelKey = $modelKey;
        $this->relatedModel = $relatedModel;
        $this->relatedForeignKey = $relatedForeignKey;
    }

    public function getRelationKey()
    {
        return $this->pluralizeRelationKey();
    }

    protected function pluralizeRelationKey()
    {
        if (strpos($this->relatedModel->tableName, '_') === false)
        {
            return \Str::plural($this->relatedModel->tableName);
        }
        else
        {
            $pluralizedPart = trim(str_replace(
                $this->model->tableName, '', $this->relatedModel->tableName
            ), '_');

            return $this->model->tableName . '_' . \Str::plural($pluralizedPart);
        }
    }

}
