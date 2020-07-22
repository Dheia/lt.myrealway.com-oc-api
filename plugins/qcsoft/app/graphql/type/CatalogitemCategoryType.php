<?php namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CatalogitemCategoryType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'CatalogitemCategory',
            'fields'       => function ()
            {
                return [
                    'id'             => Types::id(),
                    'category_id'    => Types::id(),
                    'catalogitem_id' => Types::id(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
