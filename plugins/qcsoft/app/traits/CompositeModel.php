<?php namespace Qcsoft\App\Traits;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use October\Rain\Database\Relations\MorphOne;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\Ocext\Classes\AppSchema;

/**
 * Trait CompositeModel
 * @package Qcsoft\App\Traits
 */
trait CompositeModel
{
//    TODO
//    public function hasGetMutator($name){
//        if ($name === 'page_custom_h1_title')
//        {
//            \Debugbar::info('qwer');
//
//            return true;
//        }
//
//        return parent::hasGetMutator(...func_get_args());
//    }

    public static function bootCompositeModel()
    {
        static::extend(function (Model $model)
        {
            $saveAttributes = [];

            $model->bindEvent('model.saveInternal', function () use ($model, &$saveAttributes)
            {
                foreach ($model->compositeModel as $compositeRelationName => $compositeConfig)
                {
                    $saveAttributes[$compositeRelationName] = [];

                    foreach ($model->attributes as $compositeKey => $attribute)
                    {
                        if (starts_with($compositeKey, $compositeRelationName . '_'))
                        {
                            $key = str_replace_first($compositeRelationName . '_', '', $compositeKey);

                            $saveAttributes[$compositeRelationName][$key] = $model->attributes[$compositeKey];

                            unset($model->attributes[$compositeKey]);
                        }
                    }
                }
            });

            $model->bindEvent('model.afterSave', function () use ($model, &$saveAttributes)
            {
                foreach ($saveAttributes as $compositeRelationName => $attributes)
                {
                    /** @var MorphOne $compositeRelation */
                    $compositeRelation = $model->$compositeRelationName();

                    if (!$compositeRelatedModel = $model->$compositeRelationName)
                    {
                        $compositeRelatedModelName = get_class($compositeRelation->getRelated());

                        $compositeRelatedModel = new $compositeRelatedModelName;

                        $model->$compositeRelationName = $compositeRelatedModel;
                    }

                    foreach ($attributes as $key => $value)
                    {
                        $compositeRelatedModel->$key = $value;
                    }

                    $compositeRelatedModel->save();
                }
            });
        });
    }

    protected $cattr;

    public function getAttribute($name)
    {
//        if (!$this->exists)
//        {
////            if ($name === 'catalogitem')
////            {
////                \Log::info('qwer ' . json_encode($this->relationsToArray()));
//////                \Debugbar::info($this->cattr);
////
////                $this->relations['catalogitem'] = array_get($this->relations, 'catalogitem', new Catalogitem());
////                return $this->relations['catalogitem'];
////                if (!isset($this->relations['catalogitem']))
////                {
////                    \Log::info('!isset');
////
////////                    $this->relations['catalogitem'] = new Catalogitem();
////////
////////                    return $this->relations['catalogitem'];
//////
//////                    $this->cattr = $this->cattr ?: new Catalogitem();
//////
//////                    return $this->cattr;
//////                }
//////                else
//////                {
//////                    \Debugbar::info('qqqqqqqqqq');
//////                    \Log::info('qqqqqqqqqq');
////                }
////            }
//
//            return parent::getAttribute(...func_get_args());
//        }

        if (isset($this->attributes[$name]))
        {
            return parent::getAttribute(...func_get_args());
        }

        foreach ($this->compositeModel as $compositeRelationName => $compositeConfig)
        {
            if (starts_with($name, $compositeRelationName . '_'))
            {
                if (!$compositeRelatedModel = $this->$compositeRelationName)
                {
                    return null;
                }

                $key = str_replace_first($compositeRelationName . '_', '', $name);

                return $compositeRelatedModel->getAttribute($key);
            }
        }

        return parent::getAttribute(...func_get_args());
    }

    public function scopeWithComposites(Builder $query, $compositeNames = null)
    {
        $schema = AppSchema::instance()->list;

        $model = $query->getModel();

        $table = $model->getTable();

        foreach ($this->compositeModel as $compositeRelationName => $compositeConfig)
        {
            /** @var MorphOne $compositeRelation */
            $compositeRelation = $model->$compositeRelationName();
            $compositeTable = $compositeRelation->getRelated()->getTable();
            $compositeKeyType = $compositeRelation->getMorphType();
            $compositeKeyId = $compositeRelation->getForeignKeyName();

            $compositeSelects = ["cm_$compositeRelationName.{$compositeRelationName}_id"];

            $innerSelects = ["id as {$compositeRelationName}_id","$compositeKeyType", "$compositeKeyId"];

            foreach ($schema[$compositeTable] as $column)
            {
                $columnName = $column->COLUMN_NAME;

                if (in_array($columnName, ['id', $compositeKeyType, $compositeKeyId]))
                {
                    continue;
                }

                $compositeSelects[] = "cm_$compositeRelationName.$columnName as {$compositeRelationName}_{$columnName}";

                $innerSelects[] = "$columnName";
            }

            $innerSelects = implode(',', $innerSelects);

            $query->join(
                \DB::raw("(select $innerSelects from $compositeTable) cm_$compositeRelationName"),
                "cm_$compositeRelationName.$compositeKeyId", '=', "$table.id"
            );

            $query->where("cm_$compositeRelationName.$compositeKeyType", '=', $compositeRelation->getMorphClass());

            foreach ($compositeSelects as $select)
            {
                $query->addSelect($select);
            }
        }

        foreach ($schema[$table] as $column)
        {
            $query->addSelect("$table.$column->COLUMN_NAME");
        }

        return $query;
    }

//    public function getCompositeSelects($compositeRelationName, $subselectAlias)
//    {
//        /** @var MorphOne $compositeRelation */
//        $compositeRelation = $this->$compositeRelationName();
//        $compositeTable = $compositeRelation->getRelated()->getTable();
//        $compositeKeyType = $compositeRelation->getMorphType();
//        $compositeKeyId = $compositeRelation->getForeignKeyName();
//
//        $schema = AppSchema::instance()->list;
//
//        $result = [];
//
//        foreach ($schema[$compositeTable] as $column)
//        {
//            $columnName = $column->COLUMN_NAME;
//
//            if (in_array($columnName, ['id', $compositeKeyType, $compositeKeyId]))
//            {
//                continue;
//            }
//
//            $columnAlias = "{$compositeRelationName}_{$columnName}";
//
//            $result[] = "$compositeTable.$columnName as $subselectAlias";
//        }
//
//        return $result;
//    }
//
//    public function scopeWithComposites(Builder $query, $compositeNames = null)
//    {
//        $model = $query->getModel();
//
//        $table = $model->getTable();
//
//        $aliasCount = 0;
//
//        foreach ($this->compositeModel as $compositeRelationName => $compositeConfig)
//        {
//            $aliasCount++;
//
//            /** @var MorphOne $compositeRelation */
//            $compositeRelation = $model->$compositeRelationName();
//            $compositeTable = $compositeRelation->getRelated()->getTable();
//            $compositeKeyType = $compositeRelation->getMorphType();
//            $compositeKeyId = $compositeRelation->getForeignKeyName();
//
//            $compositeSelects = $this->getCompositeSelects($compositeRelationName, "t$aliasCount");
//
//            $query->join(\DB::raw("(select * from $compositeTable) t$aliasCount"),
//                "t$aliasCount.$compositeKeyId", '=', "$table.id");
////            $query->join("$compositeTable", "$compositeTable.$compositeKeyId", '=', "$table.id");
//            $query->where("t$aliasCount.$compositeKeyType", '=', $compositeRelation->getMorphClass());
//
//            foreach ($compositeSelects as $select)
//            {
//                $query->addSelect($select);
//            }
//        }
//
//        return $query;
//    }

}
