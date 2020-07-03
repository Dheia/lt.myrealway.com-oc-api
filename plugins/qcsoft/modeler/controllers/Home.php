<?php namespace Qcsoft\Modeler\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Qcsoft\Modeler\Classes\MigrationsGenerator;
use Qcsoft\Modeler\Classes\OrmGenerator;
use Qcsoft\Modeler\Classes\OrmModel;
use Qcsoft\Modeler\Classes\OrmRelation;
use Qcsoft\Modeler\Classes\SchemaDefinition;
use Qcsoft\Modeler\Models\Attribute;
use Qcsoft\Modeler\Models\Entity;
use Qcsoft\Modeler\Models\Relation;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;

class Home extends Controller
{
    public $implement = [];

    protected static $pl = [
        'entity'    => 'entities',
        'attribute' => 'attributes',
        'relation'  => 'relations',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Qcsoft.Modeler', 'main-menu-modeler');

//        $this->addJs('/plugins/qcsoft/modeler/assets/dist/main.js');
        $this->addCss('/plugins/qcsoft/modeler/assets/diff2html.min.css');
//        $this->addCss('/plugins/qcsoft/modeler/assets/diff2html.min.js');
//        $this->addCss('/plugins/qcsoft/modeler/assets/diff2html-ui.min.js');
        $this->addJs('http://178.19.16.34:8080/main.js');
    }

    public function index()
    {
        $this->vars['schema'] = htmlspecialchars(json_encode([
            'entities'   => \Qcsoft\Modeler\Models\Entity::with('options')->get(),
            'attributes' => \Qcsoft\Modeler\Models\Attribute::orderBy('sort_order')->get(),
            'relations'  => \Qcsoft\Modeler\Models\Relation::get(),
        ]));
    }

    public function onGetMigrations()
    {
        $currentDef = new SchemaDefinition(

            Entity::with('options')->get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                }),

            Attribute::orderBy('sort_order')->get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                }),

            Relation::get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                }),
        );

        return MigrationsGenerator::generateMigrations('Qcsoft.App', $currentDef);
    }

    public function onGetOrm()
    {
        $currentDef = new SchemaDefinition(

            Entity::with('options')->get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                }),

            Attribute::orderBy('sort_order')->get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                }),

            Relation::get()
                ->map(function ($item)
                {
                    return (object)$item->toArray();
                }),
        );

        $generator = new OrmGenerator('Qcsoft.App', $currentDef);

        $models = $generator->generateOrmModels(function ($model)
        {
            /** @var OrmModel $model */

            /** @var object $attribute */
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
                    case 'string':
                        $model->addProperty('string', $field->attribute->name);
                        break;
                    case 'text':
                        $model->addProperty('string', $field->attribute->name);
                        break;
                    case 'imageUpload':
                        $model
                            ->addProperty('File', $field->attribute->name)
                            ->addClassUse('System\\Models\\File')
                            ->addRelationDecl('attachOne', $field->attribute->name, '[File::class]');

                        break;
                    default:
                        throw new \Exception('not attr type ' . $attribute->type);
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
                }

                /** @var OrmRelation $relation */
                foreach ($model->relationsTo as $relation)
                {
                    if ($relation->relation->type === 'oneToMany')
                    {
                        $relatedClassname = $relation->fromField->model->getClassname();

                        $model->addRelationDecl('hasMany',
                            $relation->keyHasMany(),
                            "[$relatedClassname::class]"
                        )
                            ->addClassUse('October\\Rain\\Database\\Collection')
                            ->addClassUse($relation->fromField->model->getQualifiedClassname())
                            ->addProperty('Collection', $relation->keyHasMany());
                    }
                }
            }
        });

        $result = [];

        foreach ($models as $model)
        {
            if (!file_exists($model['modelPath']))
            {
                file_put_contents($model['modelPath'], $model['modelCode']);
            }

            if (file_exists($model['modelBasePath']))
            {
                $oldBaseCode = file_get_contents($model['modelBasePath']);

                $builder = new DiffOnlyOutputBuilder(
                    "--- Original\n+++ New\n"
                );

                $differ = new Differ(/*$builder*/);

                $diff = $differ->diff($oldBaseCode, $model['modelBaseCode']);

                $result[] = [
                    'oldBaseCode' => $oldBaseCode,
                    'diff'        => $diff,
                    'model'       => $model,
                ];
            }
            else
            {
//                $result[] = "created {$model['modelBasePath']}\n{$model['modelBaseCode']}";
            }
        }

        return json_encode($result);
    }

}
