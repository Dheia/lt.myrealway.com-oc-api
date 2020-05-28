<?php namespace Ydnnov\Crudgen\Classes;

class DbmlHasMany
{
    /** @var DbmlModel */
    public $model;

    /** @var string */
    public $modelKey;

    /** @var DbmlModel */
    public $relatedModel;

    /** @var string */
    public $relatedForeignKey;

    /**
     * DbmlHasMany constructor.
     * @param DbmlModel $model
     * @param string $modelKey
     * @param DbmlModel $relatedModel
     * @param string $relatedForeignKey
     */
    public function __construct(DbmlModel $model, $modelKey, DbmlModel $relatedModel, $relatedForeignKey)
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
        $parts = explode('_', $this->relatedModel->tableName);

        if (count($parts) === 1)
        {
            return \Str::plural($parts[0]);
        }
        elseif (count($parts) === 2)
        {
            if ($parts[0] === $this->model->tableName)
            {
                $pluralizePart = $parts[1];
            }
            elseif ($parts[1] === $this->model->tableName)
            {
                $pluralizePart = $parts[0];
            }
            else
            {
                throw new \Exception('Relation key does not have owner model name in it');
            }

            return $this->model->tableName . '_' . \Str::plural($pluralizePart);
        }
        else
        {
            throw new \Exception('Relation key consists of more than two parts');
        }
    }

}
