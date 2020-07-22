<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class PageType extends ObjectType
{
    use ResolveFieldTrait;

    public function __construct()
    {
        $config = [
            'name'         => 'Page',
            'fields'       => function ()
            {
                return [
                    'id'               => Types::id(),
                    'owner_type'       => Types::string(),
                    'owner_id'         => Types::int(),
                    'owner'            => Types::pageOwner(),
                    'path'             => Types::string(),
                    'custom_h1_title'  => Types::string(),
                    'custom_seo_title' => Types::string(),
                    'seo_desc'         => Types::string(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
