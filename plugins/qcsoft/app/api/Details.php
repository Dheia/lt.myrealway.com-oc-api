<?php namespace Qcsoft\App\Api;

use Qcsoft\App\GraphQL\AppContext;
use Qcsoft\App\GraphQL\AppGraphQL;

class Details
{
    public function catalogitem($ids)
    {
//        sleep(3);

        $ids = '"' . implode('","', explode(',', $ids)) . '"';

        $result = AppGraphQL::execute(<<<EOT
{
    catalogitem (selectWhereIn: ["id", $ids]) {
        id
        main_image {
            title
            thumb (w: 120, h: 120, mode: "crop")
        }
        item {
            __typename
            ... on Bundle {
                id
                mini_desc
                page {
                    path
                }
                bundle_products {
                    quantity
                    product {
                        catalogitem {
                            name
                            main_image {
                                thumb (w: 35, h: 35, mode: "crop")
                            }
                        }
                    }
                }
            }
            ... on Product {
                id
                mini_desc
                page {
                    path
                }
            }
        }
    }
}
EOT
            , [], new AppContext())->data;

        return $result['catalogitem'];
    }

}
