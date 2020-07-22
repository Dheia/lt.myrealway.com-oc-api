<?php namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CatalogitemCustomergroupType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'CatalogitemCustomergroup',
            'fields'       => function ()
            {
                return [
                    'id'               => Types::id(),
                    'customergroup_id' => Types::id(),
                    'catalogitem_id'   => Types::id(),
                    'price'            => Types::int(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
