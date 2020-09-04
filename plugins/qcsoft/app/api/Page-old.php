<?php namespace Qcsoft\App\Api;

use Qcsoft\App\GraphQL\AppContext;
use Qcsoft\App\GraphQL\AppGraphQL;

class Page
{
    public function index($path){
        dump(func_get_args());die;
    }

    public function home()
    {
//        sleep(1);

        $bestsellerProductIds = [1022, 1023, 1024, 1025, 1026, 1027, 1028];
        $bestsellerBundleIds = [6, 7, 8, 10, 11, 12];

        $bestsellerProductsSelect = '"' . implode('","', $bestsellerProductIds) . '"';
        $bestsellerBundlesSelect = '"' . implode('","', $bestsellerBundleIds) . '"';

        $result = AppGraphQL::execute(<<<EOT
{
    product(selectWhereIn: ["id", $bestsellerProductsSelect]) {
        id
        default_price
        mini_desc
        catalogitem {
            name
            main_image {
                title
                thumb (w: 120, h: 120, mode: "crop")
            }
        }
        page {
            path
        }
    }
    bundle(selectWhereIn: ["id", $bestsellerBundlesSelect]) {
        id
        default_price
        mini_desc
        catalogitem {
            name
            main_image {
                title
                thumb (w: 120, h: 120, mode: "crop")
            }
        }
        page {
            path
        }
    }
}
EOT
            , [], new AppContext())->data;

        $result = [
            'product' => collect($result['product'])->map(function ($item)
            {
                $result = [
                    'id'            => $item['id'],
                    'default_price' => $item['default_price'],
                    'mini_desc'     => $item['mini_desc'],
                    'name'          => $item['catalogitem']['name'],
                    'main_image'    => $item['catalogitem']['main_image'],
                    'page_path'     => $item['page']['path'],
                ];

                return $result;
            }),
            'bundle'  => collect($result['bundle'])->map(function ($item)
            {
                $result = [
                    'id'            => $item['id'],
                    'default_price' => $item['default_price'],
                    'mini_desc'     => $item['mini_desc'],
                    'name'          => $item['catalogitem']['name'],
                    'main_image'    => $item['catalogitem']['main_image'],
                    'page_path'     => $item['page']['path'],
                ];

                return $result;
            })
        ];

        return $result;
    }

    public function product($id)
    {
//        sleep(1);
        $result = AppGraphQL::execute(<<<EOT
{
    product(selectWhere: ["id", "$id"]) {
        id
        product_code
        default_price
        mini_desc
        description
        ingredients
        catalogitem {
            id
            name
            main_image {
                title
                path
            }
            catalogitem_relevant_catalogitems {
                relevant_catalogitem {
                    id
                    name
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
        }
        page {
            path
        }
    }
}
EOT
            , [], new AppContext())->data;
//dd($result);
        $result = collect($result['product'])->map(function ($item)
        {
//            return $item;
            $result = [
                'id'            => $item['id'],
                'product_code'  => $item['product_code'],
                'default_price' => $item['default_price'],
                'mini_desc'     => $item['mini_desc'],
                'description'   => $item['description'],
                'ingredients'   => $item['ingredients'],
                'catalogitem'   => [
                    'id'             => $item['catalogitem']['id'],
                    'name'           => $item['catalogitem']['name'],
                    'main_image'     => $item['catalogitem']['main_image'],
                    'relevant_items' => collect($item['catalogitem']['catalogitem_relevant_catalogitems'])
                        ->map(function ($item)
                        {
                            return $item['relevant_catalogitem'];
                        }),
                ],
                'page'          => $item['page'],
            ];
            return $result;
            return [
                'id'   => $item['id'],
                'name' => $item['catalogitem']['name'],
                'path' => $item['page']['path'],
            ];
        });

        return $result;
    }


    public function bundle($id)
    {
//        sleep(1);
        $result = AppGraphQL::execute(<<<EOT
{
    bundle(selectWhere: ["id", "$id"]) {
        id
        #product_code
        default_price
        mini_desc
        description
        #ingredients
        catalogitem {
            id
            name
            main_image {
                title
                path
            }
            catalogitem_relevant_catalogitems {
                relevant_catalogitem {
                    id
                    name
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
        }
        bundle_products {
            id
            quantity
            sort_order
            product {
                mini_desc
                catalogitem {
                    name
                    main_image {
                        title
                        thumb (w: 120, h: 120, mode: "crop")
                    }
                }
                page {
                    path
                }
            }
        }
        page {
            path
        }
    }
}
EOT
            , [], new AppContext())->data;
//dd($result);
        $result = collect($result['bundle'])->map(function ($item)
        {
            $result = [
                'id'              => $item['id'],
                //                'product_code'  => $item['product_code'],
                'default_price'   => $item['default_price'],
                'mini_desc'       => $item['mini_desc'],
                'description'     => $item['description'],
                //                'ingredients'   => $item['ingredients'],
                'catalogitem'     => [
                    'id'             => $item['catalogitem']['id'],
                    'name'           => $item['catalogitem']['name'],
                    'main_image'     => $item['catalogitem']['main_image'],
                    'relevant_items' => collect($item['catalogitem']['catalogitem_relevant_catalogitems'])
                        ->map(function ($item)
                        {
                            return $item['relevant_catalogitem'];
                        }),
                ],
                'bundle_products' => $item['bundle_products'],
                'page'            => $item['page'],
            ];

            return $result;
        });

        return $result;
    }

}
