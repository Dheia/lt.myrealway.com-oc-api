<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class BundleProductType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'BundleProduct',
            'description'  => 'Catalog BundleProduct',
            'fields'       => function ()
            {
                return [
                    'id'             => Types::id(),
                    'bundle_id'      => Types::int(),
                    'product_id'     => Types::int(),
                    'bundle'         => Types::bundle(),
                    'product'        => Types::product(),
                    'quantity'       => Types::int(),
                    'sort_order'     => Types::int(),
                    'price_override' => Types::int(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
