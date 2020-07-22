<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;

class BundleProductCustomergroupType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name'         => 'BundleProductCustomergroup',
            'fields'       => function ()
            {
                return [
                    'id'                  => Types::id(),
                    'bundle_product_id'   => Types::id(),
                    'customergroup_id'    => Types::id(),
                    'discount_value_type' => Types::string(),
                    'discount_value'      => Types::int(),
                ];
            },
            'resolveField' => [$this, 'resolveField'],
        ];

        parent::__construct($config);
    }

}
