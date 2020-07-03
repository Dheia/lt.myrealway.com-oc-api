<?php namespace Qcsoft\Modeler;

use Qcsoft\App\Models\Product;
use Qcsoft\Modeler\Classes\OrmBelongsTo;
use Qcsoft\Modeler\Classes\OrmModel;
use Qcsoft\Modeler\Classes\SchemaDefinition;
use RainLab\Builder\Classes\DatabaseTableModel;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    protected static $dbTypes = [
        'bool'   => ['boolean', ''],
        'int'    => ['integer', '10'],
        'string' => ['string', '191'],
        'text'   => ['text', ''],
    ];

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
//        dump(Product::find(21)->main_image);
//        die;

        \Event::listen('qcsoft.modeler.generateMigration',
            function (DatabaseTableModel $model, $entity, $entityAttributes)
            {
                $columns = $entityAttributes
                    ->filter(function ($attribute)
                    {
                        if ($attribute->type === 'imageUpload')
                        {
                            return false;
                        }

                        return true;
                    })
                    ->map(function ($attribute)
                    {
                        return static::makeColumn($attribute);
                    });

                if (collect($entity->options)->firstWhere('type', 'nested_tree'))
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

                $model->columns = $columns;
            });
    }

    protected static function makeColumn($attribute)
    {
        $attribute = (object)$attribute;

        return [
            'name'           => $attribute->name,
            'type'           => static::$dbTypes[$attribute->type][0],
            'length'         => static::$dbTypes[$attribute->type][1],
            'unsigned'       => object_get($attribute, 'pk'),
            'allow_null'     => object_get($attribute, 'nullable'),
            'auto_increment' => object_get($attribute, 'autoincrement'),
            'primary_key'    => object_get($attribute, 'pk'),
            'default'        => object_get($attribute, 'default'),
        ];
    }

}
