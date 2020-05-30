<?php namespace Ydnnov\Crudgen\Classes;

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
