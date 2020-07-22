<?php namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class ProductType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Product',
            'description'  => 'Catalog product',
            'fields'       => function ()
            {
                return [
                    'id'            => Types::id(),
                    'description'   => Types::string(),
                    'default_price' => [
                        'type'        => Types::float(),
                        'description' => 'Default price',
                        'args'        => [
                            'currency' => Types::string(),
                            'format'   => Types::string(),
                        ],
                    ],
                    'catalogitem'   => [
                        'type' => Types::catalogitem(),
                    ],
                    'page'          => [
                        'type' => Types::page(),
                    ],
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
