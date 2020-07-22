<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CustomergroupType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Customergroup',
            'fields'       => function ()
            {
                return [
                    'id'               => Types::id(),
                    'name'             => Types::string(),
                    'is_default'       => Types::boolean(),
                    'discount_percent' => Types::int(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
