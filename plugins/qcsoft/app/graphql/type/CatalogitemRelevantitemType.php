<?php namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class CatalogitemRelevantitemType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'CatalogitemRelevantitem',
            'fields'       => function ()
            {
                return [
                    'id'                      => Types::id(),
                    'main_catalogitem_id'     => Types::int(),
                    'relevant_catalogitem_id' => Types::int(),
                    'main_catalogitem'        => Types::catalogitem(),
                    'relevant_catalogitem'    => Types::catalogitem(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
