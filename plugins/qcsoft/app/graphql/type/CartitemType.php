<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CartitemType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Cartitem',
            'fields'       => function ()
            {
                return [
                    'id'            => Types::id(),
                    'cart_id'       => Types::id(),
                    'sellable_type' => Types::string(),
                    'sellable_id'   => Types::int(),
                    'quantity'      => Types::int(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
