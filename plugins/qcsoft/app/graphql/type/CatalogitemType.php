<?php namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\OcHelper;
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
                    'id'                        => Types::id(),
                    'item_type'                 => Types::string(),
                    'item_id'                   => Types::int(),
                    'item'                      => Types::catalogitemItem(),
                    'main_category_id'          => Types::int(),
                    'name'                      => Types::string(),
                    'mini_desc'                 => Types::string(),
                    'main_image'                => Types::file(),
                    'catalogitem_relevant_catalogitems' => [
                        'type' => Types::listOf(Types::catalogitemRelevantitem()),
                        'args' => OcHelper::argsModelQueryMixin(),
                    ],
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
