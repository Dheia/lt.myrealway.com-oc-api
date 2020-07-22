<?php namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CatalogitemFilteroptionType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'CatalogitemFilteroption',
            'fields'       => function ()
            {
                return [
                    'id'              => Types::id(),
                    'filteroption_id' => Types::id(),
                    'catalogitem_id'  => Types::id(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
