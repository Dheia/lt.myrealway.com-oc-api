<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\OcHelper;
use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class BundleType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Bundle',
            'description'  => 'Catalog product bundles',
            'fields'       => function ()
            {
                return [
                    'id'              => Types::id(),
                    'description'     => Types::string(),
                    'default_price'   => [
                        'type'        => Types::float(),
                        'description' => 'Default price',
                        'args'        => [
                            'currency' => Types::string(),
                            'format'   => Types::string(),
                        ],
                    ],
                    'catalogitem'     => Types::catalogitem(),
                    'page'            => Types::page(),
                    'bundle_products' => [
                        'type' => Types::listOf(Types::bundleProduct()),
                        'args' => OcHelper::argsModelQueryMixin(),
                    ],
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

//    public function resolveBundle_products(Bundle $bundle, $args, AppContext $appContext, ResolveInfo $resolveInfo)
//    {
//        return $appContext->handleEloquentRelation($bundle, $args, $resolveInfo, 'bundle_products');
//    }

}
