<?php namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CatalogitemType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Catalogitem',
            'fields'       => function ()
            {
                return [
                    'id'               => Types::id(),
                    'item_type'        => Types::string(),
                    'item_id'          => Types::int(),
                    'item'             => Types::catalogitemItem(),
                    'main_category_id' => Types::int(),
                    'name'             => Types::string(),
                    'main_image'       => Types::file(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
