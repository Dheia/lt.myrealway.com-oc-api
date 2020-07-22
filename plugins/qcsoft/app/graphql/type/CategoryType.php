<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CategoryType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Category',
            'description'  => 'Category',
            'fields'       => function ()
            {
                return [
                    'id'          => Types::id(),
                    'parent_id'   => Types::int(),
                    'nest_left'   => Types::int(),
                    'nest_right'  => Types::int(),
                    'nest_depth'  => Types::int(),
                    'name'        => Types::string(),
                    'description' => Types::string(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
