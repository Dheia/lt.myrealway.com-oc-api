<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CustompageType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Custompage',
            'description'  => 'Custompage',
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
