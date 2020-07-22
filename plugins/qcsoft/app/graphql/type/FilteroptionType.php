<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class FilteroptionType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Filteroption',
            'description'  => 'Filteroption',
            'fields'       => function ()
            {
                return [
                    'id'             => Types::id(),
                    'filter_id'      => Types::id(),
                    'name'           => Types::string(),
                    'sort_order'     => Types::int(),
                    'is_in_bundles'  => Types::boolean(),
                    'is_in_products' => Types::boolean(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
