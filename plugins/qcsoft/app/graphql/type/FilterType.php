<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class FilterType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Filter',
            'description'  => 'Filter',
            'fields'       => function ()
            {
                return [
                    'id'             => Types::id(),
                    'name'           => Types::string(),
                    'sort_order'     => Types::int(),
                    'is_in_bundles'  => Types::boolean(),
                    'is_in_products' => Types::boolean(),
                    'filteroptions'  => Types::listOf(Types::filteroption())
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
