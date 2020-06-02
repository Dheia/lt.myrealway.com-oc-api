<?php namespace Qcsoft\Crudgen\Classes;

class OrmBelongsTo
{
    /** @var OrmModel */
    public $model;

    /** @var string */
    public $modelForeignKey;

    /** @var OrmModel */
    public $relatedModel;

    /** @var string */
    public $relatedKey;

    /**
     * OrmBelongsTo constructor.
     * @param OrmModel $model
     * @param string $modelForeignKey
     * @param OrmModel $relatedModel
     * @param string $relatedKey
     */
    public function __construct(OrmModel $model, string $modelForeignKey, OrmModel $relatedModel, string $relatedKey)
    {
        $this->model = $model;
        $this->modelForeignKey = $modelForeignKey;
        $this->relatedModel = $relatedModel;
        $this->relatedKey = $relatedKey;
    }

    public function getRelationKey()
    {
        return preg_replace('/(.+)_id$/', '$1', $this->modelForeignKey);
    }

}
