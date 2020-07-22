<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class GenericpageType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Genericpage',
            'description'  => 'Genericpage',
            'fields'       => function ()
            {
                return [
                    'id'      => Types::id(),
                    'name'    => Types::string(),
                    'content' => Types::string(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];
        parent::__construct($config);
    }

}
