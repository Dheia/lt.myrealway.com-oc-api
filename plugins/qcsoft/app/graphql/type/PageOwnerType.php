<?php

namespace Qcsoft\App\GraphQL\Type;

use Qcsoft\App\GraphQL\Types;
use GraphQL\Type\Definition\UnionType;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Product;

class PageOwnerType extends UnionType
{
    public function __construct()
    {
        $config = [
            'name'        => 'PageOwner',
            'types'       => function ()
            {
                return [
                    Types::genericpage(),
                    Types::bundle(),
                    Types::product(),
                ];
            },
            'resolveType' => function ($value)
            {
                if ($value instanceof Genericpage)
                {
                    return Types::genericpage();
                }
                elseif ($value instanceof Bundle)
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
