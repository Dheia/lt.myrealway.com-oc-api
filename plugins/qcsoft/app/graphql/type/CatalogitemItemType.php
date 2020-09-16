<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\UnionType;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Custompage;
use Qcsoft\App\Models\Product;

class CatalogitemItemType extends UnionType
{
    public function __construct()
    {
        $config = [
            'name'        => 'CatalogitemItem',
            'types'       => function ()
            {
                return [
                    Types::bundle(),
                    Types::product(),
                ];
            },
            'resolveType' => function ($value)
            {
                if ($value instanceof Bundle)
                {
                    return Types::bundle();
                }
                elseif ($value instanceof Product)
                {
                    return Types::product();
                }
            }
        ];

        parent::__construct($config);
    }
}
