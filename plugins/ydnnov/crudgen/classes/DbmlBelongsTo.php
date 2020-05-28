<?php namespace Ydnnov\Crudgen\Classes;

class DbmlBelongsTo
{
    /** @var DbmlModel */
    public $model;

    /** @var string */
    public $modelForeignKey;

    /** @var DbmlModel */
    public $relatedModel;

    /** @var string */
    public $relatedKey;

    /**
     * DbmlBelongsTo constructor.
     * @param DbmlModel $model
     * @param string $modelForeignKey
     * @param DbmlModel $relatedModel
     * @param string $relatedKey
     */
    public function __construct(DbmlModel $model, $modelForeignKey, DbmlModel $relatedModel, $relatedKey)
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
