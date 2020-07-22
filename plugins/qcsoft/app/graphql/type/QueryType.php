<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\AppContext;
use Qcsoft\App\GraphQL\OcHelper;
use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\BundleProductCustomergroup;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;
use System\Models\File;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name'         => 'Query',
            'fields'       => [
                'bundle'                       => $this->eloquentModel(Bundle::class),
                'bundle_product'               => $this->eloquentModel(BundleProduct::class),
                'bundle_product_customergroup' => $this->eloquentModel(BundleProductCustomergroup::class),
                'catalogitem'                  => $this->eloquentModel(Catalogitem::class),
                'category'                     => $this->eloquentModel(Category::class),
                'file'                         => $this->eloquentModel(File::class),
                'filter'                       => $this->eloquentModel(Filter::class),
                'filteroption'                 => $this->eloquentModel(Filteroption::class),
                'page'                         => $this->eloquentModel(Page::class),
                'product'                      => $this->eloquentModel(Product::class),

                'bundle_count'                       => $this->eloquentModelCount(),
                'bundle_product_count'               => $this->eloquentModelCount(),
                'bundle_product_customergroup_count' => $this->eloquentModelCount(),
                'catalogitem_count'                  => $this->eloquentModelCount(),
                'category_count'                     => $this->eloquentModelCount(),
                'file_count'                         => $this->eloquentModelCount(),
                'filter_count'                       => $this->eloquentModelCount(),
                'filteroption_count'                 => $this->eloquentModelCount(),
                'page_count'                         => $this->eloquentModelCount(),
                'product_count'                      => $this->eloquentModelCount(),
            ],
            'resolveField' => function ($rootValue, $args, AppContext $context, ResolveInfo $info)
            {
                if (ends_with($info->fieldName, '_count'))
                {
                    $count = true;
                    $fieldName = str_replace_last('_count', '', $info->fieldName);
                }
                else
                {
                    $count = false;
                    $fieldName = $info->fieldName;
                }

                if (in_array($fieldName, [
                    'bundle',
                    'bundle_product',
                    'bundle_product_customergroup',
                    'catalogitem',
                    'category',
                    'file',
                    'filter',
                    'filteroption',
                    'page',
                    'product',
                ]))
                {
                    $modelClass = 'Qcsoft\\App\\Models\\' . \Str::studly($fieldName);

                    if ($count)
                    {
                        return OcHelper::handleEloquentModelCount($rootValue, $args, $info, $modelClass);
                    }
                    else
                    {
                        return OcHelper::handleEloquentModel($rootValue, $args, $info, $modelClass);
                    }
                }

//                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    protected function eloquentModel($classname)
    {
        $typeClass = lcfirst($basename = class_basename($classname));

        $result = [
            'type'        => Types::listOf(Types::$typeClass()),
            'description' => "$basename eloquent model",
            'args'        => [] + OcHelper::argsModelQueryMixin(),
        ];

        return $result;
    }

    protected function eloquentModelCount()
    {
        $result = [
            'type' => Types::int(),
            'args' => [] + OcHelper::argsModelQueryMixin(),
        ];

        return $result;
    }

}
