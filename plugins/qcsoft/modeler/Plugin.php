<?php namespace Qcsoft\Modeler;

use Qcsoft\Modeler\Classes\OrmField;
use Qcsoft\Modeler\Classes\OrmModel;
use Qcsoft\Modeler\Classes\OrmRelation;
use RainLab\Builder\Classes\DatabaseTableModel;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    protected static $dbTypes = [
        'bool'      => ['boolean', ''],
        'int'       => ['integer', '10'],
        'integer'   => ['integer', '10'],
        'timestamp' => ['timestamp', ''],
        'float'     => ['double', ''],
        'double'    => ['double', ''],
        'number'    => ['decimal', ''],
        'decimal'   => ['decimal', ''],
        'string'    => ['string', '191'],
        'text'      => ['text', ''],
    ];

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        \Event::listen('qcsoft.modeler.generateMigration',
            function (DatabaseTableModel $dbTableModel, OrmModel $model)
            {
                $hasNestedTree = collect($model->entity->options)
                    ->where('is_enabled', 1)
                    ->firstWhere('type', 'nested_tree');

                $fieldsWithColumn = $model->fields
                    ->filter(function ($field)
                    {
                        if ($field->attribute->type === 'imageUpload')
                        {
                            return false;
                        }

                        return true;
                    });

                $columns = [];

                foreach ($fieldsWithColumn as $field)
                {
                    $params = (array)$field->attribute;

                    if ($params['type'] === 'slug')
                    {
                        $params['type'] = 'string';
                    }

                    $columns[] = static::makeColumn($params);

                    if ($field->attribute->name === 'id' && $hasNestedTree)
                    {
                        foreach ([
                                     'parent_id'  => 'int',
                                     'nest_left'  => 'int',
                                     'nest_right' => 'int',
                                     'nest_depth' => 'int',
                                 ] as $columnName => $columnType)
                        {
                            $columns[] = static::makeColumn([
                                'name'     => $columnName,
                                'type'     => $columnType,
                                'nullable' => true,
                            ]);
                        }
                    }
                }

                $dbTableModel->columns = $columns;
            });

        \Event::listen('qcsoft.modeler.generateOrm', function (OrmModel $model)
        {
            /** @var OrmField $field */
            foreach ($model->fields as $field)
            {
                switch ($field->attribute->type)
                {
                    case 'bool':
                    case 'boolean':
                        $model->addProperty('boolean', $field->attribute->name);
                        break;
                    case 'int':
                    case 'integer':
                        $model->addProperty('int', $field->attribute->name);
                        break;
                    case 'timestamp':
                        $model->addProperty('timestamp', $field->attribute->name);
                        break;
                    case 'float':
                        $model->addProperty('float', $field->attribute->name);
                        break;
                    case 'double':
                        $model->addProperty('double', $field->attribute->name);
                        break;
                    case 'number':
                    case 'decimal':
                        $model->addProperty('decimal', $field->attribute->name);
                        break;
                    case 'string':
                        $model->addProperty('string', $field->attribute->name);
                        break;
                    case 'text':
                        $model->addProperty('string', $field->attribute->name);
                        break;
                    case 'slug':
                        $sluggableParams = json_decode($field->attribute->params);

                        $model
                            ->addProperty('string', $field->attribute->name)
                            ->addClassUse('October\\Rain\\Database\\Traits\\Sluggable')
                            ->addTrait('Sluggable')
                            ->setExtraProp(
                                'slugs',
                                "['{$field->attribute->name}' => '{$sluggableParams->from}'];"
                            );

                        break;
                    case 'imageUpload':
                        $model
                            ->addProperty('File', $field->attribute->name)
                            ->addClassUse('System\\Models\\File')
                            ->addRelationDecl('attachOne', $field->attribute->name, '[File::class]');
                        break;
                    default:
                        throw new \Exception('not attr type ' . $field->attribute->type);
                }

                /** @var OrmRelation $relation */
                foreach ($model->relationsFrom as $relation)
                {
                    if ($relation->relation->type === 'oneToMany')
                    {
                        $relatedClassname = $relation->toField->model->getClassname();

                        $model->addRelationDecl('belongsTo',
                            $relation->keyBelongsTo(),
                            "[$relatedClassname::class]"
                        )
                            ->addClassUse($relation->toField->model->getQualifiedClassname())
                            ->addProperty($relatedClassname, $relation->keyBelongsTo());
                    }
                    elseif ($relation->relation->type === 'morphOne')
                    {
                        $relatedClassname = $relation->toField->model->getClassname();

                        $model->addRelationDecl('morphTo',
                            $relation->keyBelongsTo(),
                            '[]'
                        )
                            ->addClassUse($relation->toField->model->getQualifiedClassname())
                            ->addProperty('mixed', $relation->keyBelongsTo());
                    }
                    elseif ($relation->relation->type === 'morphMany')
                    {
                        $relatedClassname = $relation->toField->model->getClassname();

                        $model->addRelationDecl('morphTo',
                            $relation->keyBelongsTo(),
                            '[]'
                        )
                            ->addClassUse($relation->toField->model->getQualifiedClassname())
                            ->addProperty('mixed', $relation->keyBelongsTo());
                    }
                }

                /** @var OrmRelation $relation */
                foreach ($model->relationsTo as $relation)
                {
                    if ($relation->relation->type === 'oneToMany')
                    {
                        $relatedClassname = $relation->fromField->model->getClassname();
                        $delete = $relation->relation->is_delete ? 'true' : 'false';

                        $model->addRelationDecl('hasMany',
                            $relation->keyHasMany(),
                            "[$relatedClassname::class, 'delete' => $delete]"
                        )
                            ->addClassUse('October\\Rain\\Database\\Collection')
                            ->addClassUse($relation->fromField->model->getQualifiedClassname())
                            ->addProperty('Collection', $relation->keyHasMany());
                    }
                    elseif ($relation->relation->type === 'morphOne')
                    {
                        $morphName = str_replace_last('_id', '', $relation->fromField->attribute->name);
                        $relatedClassname = $relation->fromField->model->getClassname();
                        $relatedClassnameQualified = $relation->fromField->model->getQualifiedClassname();
                        $delete = $relation->relation->is_delete ? 'true' : 'false';

                        $model
                            ->addRelationDecl('morphOne', $relation->keyHasOne(),
                                "[$relatedClassname::class, 'name' => '$morphName', 'delete' => $delete]"
                            )
                            ->addClassUse($relatedClassnameQualified)
                            ->addProperty($relatedClassname, $relation->keyHasOne());
                    }
                    elseif ($relation->relation->type === 'morphMany')
                    {
                        $morphName = str_replace_last('_id', '', $relation->fromField->attribute->name);
                        $relatedClassname = $relation->fromField->model->getClassname();
                        $relatedClassnameQualified = $relation->fromField->model->getQualifiedClassname();
                        $delete = $relation->relation->is_delete ? 'true' : 'false';

                        $model
                            ->addRelationDecl('morphMany', $relation->keyHasMany(),
                                "[$relatedClassname::class, 'name' => '$morphName', 'delete' => $delete]"
                            )
                            ->addClassUse($relatedClassnameQualified)
                            ->addProperty($relatedClassname, $relation->keyHasMany());
                    }
                }
            }

            foreach ($model->entity->options as $option)
            {
                $option = (object)$option;

                if (!$option->is_enabled)
                {
                    continue;
                }
                
                if ($option->type === 'composite')
                {
                    $model
                        ->addClassUse('Qcsoft\\App\\Traits\\CompositeModel')
                        ->addTrait('CompositeModel')
                        ->setExtraPropMulti('compositeModel', $option->value['relation'], "[]");
                }
                elseif ($option->type === 'nested_tree')
                {
                    $model
                        ->addClassUse('October\\Rain\\Database\\Traits\\NestedTree')
                        ->addTrait('NestedTree')
                        ->addProperty('int', 'parent_id')
                        ->addProperty('int', 'nest_depth')
                        ->addProperty('int', 'nest_left')
                        ->addProperty('int', 'nest_right');
                }
            }
        });

        \Event::listen('qcsoft.modeler.generateColumnsYaml', function (OrmModel $model)
        {
        });

        \Event::listen('qcsoft.modeler.generateFieldsYaml', function (OrmModel $model)
        {
        });
    }

    protected static function makeColumn($params)
    {
        return [
            'name'           => $params['name'],
            'type'           => static::$dbTypes[$params['type']][0],
            'length'         => static::$dbTypes[$params['type']][1],
            'unsigned'       => array_get($params, 'pk'),
            'allow_null'     => array_get($params, 'nullable'),
            'auto_increment' => array_get($params, 'autoincrement'),
            'primary_key'    => array_get($params, 'pk'),
            'default'        => array_get($params, 'default'),
        ];
    }

}
