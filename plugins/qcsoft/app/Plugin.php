<?php namespace Qcsoft\App;

use Backend\Classes\Controller;
use Cms\Classes\Router;
use October\Rain\Database\Relations\Relation;
use Qcsoft\App\Components\Cart;
use Qcsoft\App\Components\CatalogitemList;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\CatalogitemRelevantitem;
use Qcsoft\App\Models\Customer;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Filteroption;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use System\Models\File;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            CatalogitemList::class => 'catalogitemList',
            Cart::class            => 'cart',
        ];
    }

    public function registerFormWidgets()
    {
        return [
            FormWidgets\BundleProductList::class => 'bundleProductList',
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
//        \Route::get('/api/qwer', function ()
//        {
//            dump(\JWTAuth::toUser('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjIxLCJpc3MiOiJodHRwOi8vbXJ3c2hvcC50ay9hcGkvcXdlciIsImlhdCI6MTU5NzM4MzgzNSwiZXhwIjoxNTk3Mzg3NDM1LCJuYmYiOjE1OTczODM4MzUsImp0aSI6IkhudjJjbElIa25UTGQ0M1oifQ.C4IpC6GeBysjMRj60hi8vRdyG6zzIOBnZH1Z6LINFOQ'));
//            dump(get_class(resolve('tymon.jwt.auth')));
//            echo(\JWTAuth::attempt([
//                'email'    => 'qwer@asdf.com',
//                'password' => 'qweasd123',
//            ]));
//            die;
//        });

        \Event::listen('cms.beforeRoute', function () use (&$pageObj, &$pageObjVarName)
        {
            \File::cleanDirectory(storage_path('cms/twig'));
        });

        Relation::morphMap([
            'catalogitem' => Catalogitem::class,
            'genericpage' => Genericpage::class,
            'bundle'      => Bundle::class,
            'product'     => Product::class,
        ]);

//        \Event::listen('qcsoft.cms.registerPageTypes', function ()
//        {
//            return [
//                Genericpage::class,
//                Product::class,
//                Category::class,
//            ];
//        });

        User::extend(function (User $user)
        {
            $user->bindEvent('model.afterCreate', function () use ($user)
            {
                /** @var Customergroup $defaultCustomergroup */
                $defaultCustomergroup = Customergroup::where('is_default', true)->first();

                $customer = new Customer();

                $customer->group_id = $defaultCustomergroup->id;
                $customer->user_id = $user->id;

                $customer->save();
            });
        });

        Controller::extend(function (Controller $controller)
        {
            if (starts_with(get_class($controller), 'Qcsoft\\App\\Controllers'))
            {
                $controller->addJs('/plugins/qcsoft/ocext/assets/js/dist/main.js');
//                $controller->addJs('http://178.19.16.34:8081/main.js');
                $controller->addJs('/plugins/qcsoft/app/assets/dist/main.js');
            }
        });

        \Event::listen('cms.router.beforeRoute', function ($url, Router $router) use (&$pageObj)
        {
            if (starts_with($url, '/image/'))
            {
                $sizes = [
                    'sm'   => [120, 120, ['mode' => 'crop']],
                    'md'   => [200, 200, ['mode' => 'crop']],
                    'full' => 'full',
                ];

                $params = explode('/', str_replace('/image/', '', $url));

                if (count($params) !== 2)
                {
                    return false;
                }

                list($id, $size) = $params;

                if (!$config = array_get($sizes, $size))
                {
                    return false;
                }

                if (!$image = File::find($id))
                {
                    return false;
                }

                $dir = "apicache/image/$size";

                if (\File::exists("$dir/$image->id"))
                {
                    $result = \File::get("$dir/$image->id");
                }
                else
                {
                    if (!\File::exists(storage_path($dir)))
                    {
                        \File::makeDirectory(storage_path($dir), 511, true);
                    }

                    $result = $image->getThumb(...$config);

                    $result = preg_replace('/^https?:\/\/[^\/]+\//', '', $result);

                    file_put_contents(storage_path("$dir/$image->id"), $result);
                }

//                $result = [
//                    'id'  => $image->id,
//                    $size => $result,
//                ];

                header('Access-Control-Allow-Origin: *');

                echo $result;

                die;
            }

            if (starts_with($url, '/api/'))
            {
                if ($url === '/api/user')
                {
                    $token = \JWTAuth::getToken();
                    echo $token;
                    die;
                }

//                function fillRandomRelevantCatalogitems()
//                {
//                    CatalogitemRelevantitem::truncate();
//
//                    $catalogitems = Catalogitem::all();
//
//                    $all_ids = $catalogitems->pluck('id');
//
//                    foreach ($catalogitems as $ci)
//                    {
//                        foreach (range(1, rand(3, 7)) as $i)
//                        {
//                            do
//                            {
//                                $relevant_id = $all_ids[rand(0, count($all_ids) - 1)];
//                            }
//                            while ($relevant_id == $ci['id']);
//
//                            CatalogitemRelevantitem::insert([
//                                'main_catalogitem_id'     => $ci['id'],
//                                'relevant_catalogitem_id' => $relevant_id,
//                            ]);
//                        }
//                    }
//                    die;
//                }
//
//                function fillRandomFilteroptions()
//                {
//                    CatalogitemFilteroption::truncate();
//
//                    $filteroptions = Filteroption::all();
//
//                    $all_filteroption_ids = $filteroptions->pluck('id');
//
//                    foreach (Catalogitem::all() as $ci)
//                    {
//                        $randomized_filteroption_ids = $all_filteroption_ids->shuffle()->take(rand(3, 7));
//
//                        foreach ($randomized_filteroption_ids as $filteroption_id)
//                        {
//                            CatalogitemFilteroption::insert([
//                                'catalogitem_id'  => $ci['id'],
//                                'filteroption_id' => $filteroption_id,
//                            ]);
//                        }
//                    }
//                    die;
//                }
//
////                fillRandomFilteroptions();
////                fillRandomRelevantCatalogitems();
//
////                dump(
////                    Catalogitem::whereIn('id', [977, 1009, 1000])
////                        ->with(['catalogitem_relevant_catalogitems','catalogitem_relevant_catalogitems.relevant_catalogitem'])
////                        ->get()
////                        ->toArray()
////                );
////                die;
////                /** @var Catalogitem $catalogitem */
////                $catalogitem=Catalogitem::find(977);
////                dump($catalogitem->catalogitem_relevant_catalogitems);
////                dump($catalogitem ->toArray());
////                die;

                $queryStr = str_replace_first('/api/', '', $url);

                $handlers = [
                    'base'        => \Qcsoft\App\Api\Base::class,
                    'catalogitem' => \Qcsoft\App\Api\Catalogitem::class,
                    'page'        => \Qcsoft\App\Api\Page::class,
                    'image'       => \Qcsoft\App\Api\Image::class,
                    //                    'details'     => Details::class,
                ];

                foreach ($handlers as $segment => $classname)
                {
                    if (!starts_with($queryStr, "$segment/") && ($queryStr !== $segment))
                    {
                        continue;
                    }

                    $handler = new $classname();

                    $query = str_replace_first("$segment", '', $queryStr);
                    $query = trim($query, '/ ');

                    $parts = array_filter(explode('/', $query));

                    $filepath = $segment;

                    if (!count($parts))
                    {
                        $method = 'index';

                        $params = [];
                    }
                    else
                    {
                        $method = $parts[0];

                        if (!method_exists($handler, $method))
                        {
                            $method = 'index';

                            $params = $parts;

                            $filepath .= '/' . implode('/', $parts);
                        }
                        else
                        {
                            $filepath .= '/' . implode('/', $parts);

                            array_shift($parts);

                            $params = $parts;
                        }
                    }

                    try
                    {
                        $result = $handler->$method(...$params);
                    }
                    catch (\Error $e)
                    {
                        return false;   // return 404 in case of any error
                    }

                    if ($result === false)
                    {
                        return false;
                    }

                    $parts = array_filter(explode('/', $filepath));

                    $filename = array_pop($parts);

                    if (!is_dir($dir = storage_path('apicache')))
                    {
                        mkdir($dir);
                    }

                    foreach ($parts as $part)
                    {
                        if (!is_dir($dir = "$dir/$part"))
                        {
                            mkdir($dir);
                        }
                    }

                    $result = json_encode($result, JSON_NUMERIC_CHECK);

                    file_put_contents("$dir/$filename.json", $result);

                    header('Access-Control-Allow-Origin: *');

                    header('Content-Type: application/json', true, 200);

                    echo $result;

                    die;
                }

                return false;
            }

            if (starts_with($url, '/graphql'))
            {
                $appGraphQL = new \Qcsoft\App\GraphQL\AppGraphQL();

                header('Access-Control-Allow-Origin: *');

                echo $appGraphQL->handle();

                die;
            }

            if ($url !== '/')
            {
                $url = trim($url, '/');
            }

            if ($page = Page::where('path', $url)->first())
            {
                $pageObj = $page->owner;

                if ($url === '/')
                {
                    $filename = 'home';
                }
                elseif (get_class($pageObj) !== Genericpage::class)
                {
                    $filename = \Str::snake(class_basename($pageObj), '-');
                }
                else
                {
                    $filename = $url;
                }
            }
            else
            {
                $filename = $url = '404';

                \Cms\Classes\Controller::getController()->setStatusCode(404);

                $pageObj = Page::where('path', '404')->first()->owner;
            }

            return \Cms\Classes\Page::loadCached('default', $filename);
        });

        \Event::listen('cms.page.init', function (
            \Cms\Classes\Controller $controller,
            \Cms\Classes\Page $page
        ) use (&$pageObj)
        {
            $controller->vars['pageObj'] = $pageObj;

            if (\Request::ajax() && \Request::input('noLayout'))
            {
                $controller->vars['noLayout'] = true;
            }

//            $pageType = \Str::snake(class_basename($pageObj), '-');
//
//            $genericBlocks = GenericBlock
//                ::where(function ($query) use ($pageType, $pageObj)
//                {
//                    $query
//                        ->where('owner_type', $pageType)
//                        ->where(function ($query) use ($pageType, $pageObj)
//                        {
//                            $query
//                                ->where('owner_id', $pageObj->id)
//                                ->orWhere('owner_id', null);
//                        });
//                })
//                ->orWhere('owner_type', null)
//                ->get();
//
//            $layoutBlocks = new GenericBlock();
//            $layoutBlocks->children = $genericBlocks->where('owner_type', null)->toNested();
//            $controller->vars['layoutBlocks'] = $layoutBlocks;
//
//            $pageBlocks = new GenericBlock();
//            $pageBlocks->children = $genericBlocks->where('owner_type', $pageType)->toNested();
//            $controller->vars['pageBlocks'] = $pageBlocks;
        });
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'admin'               => function ()
                {
                    return \BackendAuth::check();
                },
                'renderAdminPartials' => function ()
                {
                    return \BackendAuth::check() && !\Request::input('suppressAdminPartials');
                },
                'headerMenu1'         => function ()
                {
                    return [
                        [
                            'title' => 'Pristatymas',
                            'url'   => '',
                        ],
                        [
                            'title' => 'Apmokėjimas',
                            'url'   => '',
                        ],
                        [
                            'title' => 'Grąžinimas ir garantija',
                            'url'   => '',
                        ],
                    ];
                },
                'headerMenu2'         => function ()
                {
                    return [
                        [
                            'title' => 'Produktų katalogas',
                            'url'   => 'product-catalog',
                        ],
                        [
                            'title' => 'Apie mus',
                            'url'   => '',
                        ],
                        [
                            'title' => 'Informacija',
                            'url'   => '',
                        ],
                        [
                            'title' => 'Kontaktai',
                            'url'   => '',
                        ],
                    ];
                },
                'footer'              => function ()
                {
                    return [
                        [
                            'label'    => 'Informacija',
                            'type'     => 'menu',
                            'children' => [
                                [
                                    'label' => 'Saugumo politika',
                                ],
                                [
                                    'label' => 'Naudojimosi taisyklės',
                                ],
                                [
                                    'label' => 'Pristatymas ir apmokėjimas',
                                ],
                                [
                                    'label' => 'Prekių grąžinimas ir keitimas',
                                ],
                                [
                                    'label' => 'Klientų aptarnavimas',
                                ],
                            ],
                        ],
                        [
                            'label'    => 'Klientams',
                            'type'     => 'menu',
                            'children' => [
                                [
                                    'label' => 'Mano paskyra',
                                ],
                                [
                                    'label' => 'Akcijos',
                                ],
                                [
                                    'label' => 'Kur įsigyti?',
                                ],
                                [
                                    'label' => 'Naujienų prenumerata',
                                ],
                                [
                                    'label' => 'Naujienos',
                                ],
                            ],
                        ],
                        [
                            'label'    => 'Susisiekite',
                            'type'     => 'list',
                            'children' => [
                                [
                                    'label' => 'UAB "REAL WAY"',
                                ],
                                [
                                    'label' => 'Įmonės kodas 300639047',
                                ],
                                [
                                    'label' => 'Konstitucijos pr.15-23, Vilnius',
                                ],
                                [
                                    'label' => '+370 52195340',
                                ],
                                [
                                    'label' => 'info@myrealway.com',
                                ],
                                [
                                    'label' => 'Darbo laikas I-V 09:00 - 17:00',
                                ],
                            ],
                        ],
                        [
                            'label'   => 'Sekite mus',
                            'type'    => 'partial',
                            'partial' => 'footer/column-follow',
                        ],
                        [
                            'label'   => 'Saugiais mokėjimais rūpinasi',
                            'type'    => 'partial',
                            'partial' => 'footer/column-payment',
                        ],
                    ];
                },
            ]
        ];
    }

//    public function registerMarkupTags()
//    {
//        return [
//            'functions' => [
//                'admin'               => function ()
//                {
//                    return \BackendAuth::check();
//                },
//                'renderAdminPartials' => function ()
//                {
//                    return \BackendAuth::check() && !\Request::input('suppressAdminPartials');
//                },
//                'headerMenu1'         => function ()
//                {
//                    return [
//                        [
//                            'title' => 'Delivery',
//                            'url'   => '',
//                        ],
//                        [
//                            'title' => 'Pay methods',
//                            'url'   => '',
//                        ],
//                        [
//                            'title' => 'Warranty and cashback',
//                            'url'   => '',
//                        ],
//                    ];
//                },
//                'headerMenu2'         => function ()
//                {
//                    return [
//                        [
//                            'title' => 'Product catalog',
//                            'url'   => 'product-catalog',
//                        ],
//                        [
//                            'title' => 'About us',
//                            'url'   => '',
//                        ],
//                        [
//                            'title' => 'Information',
//                            'url'   => '',
//                        ],
//                        [
//                            'title' => 'Contacts',
//                            'url'   => '',
//                        ],
//                    ];
//                },
//                'footer'              => function ()
//                {
//                    return [
//                        [
//                            'label'    => 'Information',
//                            'type'     => 'menu',
//                            'children' => [
//                                [
//                                    'label' => 'Privacy policy',
//                                ],
//                                [
//                                    'label' => 'Usage terms',
//                                ],
//                                [
//                                    'label' => 'Payment and delivery',
//                                ],
//                                [
//                                    'label' => 'Warranty and cashback',
//                                ],
//                                [
//                                    'label' => 'Client service',
//                                ],
//                            ],
//                        ],
//                        [
//                            'label'    => 'Customers',
//                            'type'     => 'menu',
//                            'children' => [
//                                [
//                                    'label' => 'My account',
//                                ],
//                                [
//                                    'label' => 'Sale',
//                                ],
//                                [
//                                    'label' => 'Where to get',
//                                ],
//                                [
//                                    'label' => 'Newsletter subscription',
//                                ],
//                                [
//                                    'label' => 'News',
//                                ],
//                            ],
//                        ],
//                        [
//                            'label'    => 'Contact us',
//                            'type'     => 'list',
//                            'children' => [
//                                [
//                                    'label' => 'UAB "REAL WAY"',
//                                ],
//                                [
//                                    'label' => 'Company code 300639047',
//                                ],
//                                [
//                                    'label' => 'Constitution av. 15-23, Vilnius',
//                                ],
//                                [
//                                    'label' => '+370 52195340',
//                                ],
//                                [
//                                    'label' => 'info@myrealway.com',
//                                ],
//                                [
//                                    'label' => 'Work hours Mo-Fr 09:00 - 17:00',
//                                ],
//                            ],
//                        ],
//                        [
//                            'label'   => 'Follow us',
//                            'type'    => 'partial',
//                            'partial' => 'footer/column-follow',
//                        ],
//                        [
//                            'label'   => 'Safe payment with',
//                            'type'    => 'partial',
//                            'partial' => 'footer/column-payment',
//                        ],
//                    ];
//                },
//                //                'headerMenu' => function ()
//                //                {
//                //                    return [
//                //                        [
//                //                            'title'    => 'About us',
//                //                            'url'      => '',
//                //                            'children' => [
//                //                                [
//                //                                    'title' => 'Alliance',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Scientific potential',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Quality control',
//                //                                    'url'   => '',
//                //                                ],
//                //                            ],
//                //                        ],
//                //                        /*[
//                //                            'title'    => 'Bioregulators',
//                //                            'url'      => '',
//                //                            'children' => [
//                //                                [
//                //                                    'title' => 'About peptides',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'MRW bioregulators',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'MRW peptides',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Our advantages',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Select bioregulator',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Product catalog PDF',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Clinical trials PDF',
//                //                                    'url'   => '',
//                //                                ],
//                //                            ],
//                //                        ],
//                //                        [
//                //                            'title'    => 'Antiparasitic',
//                //                            'url'      => '',
//                //                            'children' => [],
//                //                        ],
//                //                        [
//                //                            'title' => 'PROTECTION',
//                //                            'url'   => '',
//                //                        ],
//                //                        [
//                //                            'title'    => 'Practical use',
//                //                            'url'      => '',
//                //                            'children' => [
//                //                                [
//                //                                    'title' => 'Hair and Skin',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Preventive medicine',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Muscles and Ligaments',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Men\'s health',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Weight loss',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Women\'s health',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Practical protocols PDF',
//                //                                    'url'   => '',
//                //                                ],
//                //                            ],
//                //                        ],*/
//                //                        [
//                //                            'title'    => 'Blog',
//                //                            'url'      => '',
//                //                            'children' => [
//                //                                [
//                //                                    'title' => 'FAQ',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'News',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Events',
//                //                                    'url'   => '',
//                //                                ],
//                //                                [
//                //                                    'title' => 'Video',
//                //                                    'url'   => '',
//                //                                ],
//                //                            ],
//                //                        ],
//                //                        [
//                //                            'title' => 'Partnership',
//                //                            'url'   => '',
//                //                        ],
//                //                        [
//                //                            'title' => 'E-SHOP',
//                //                            'url'   => 'product-catalog',
//                //                            'class' => 'e-shop'
//                //                        ],
//                //                        //                        [
//                //                        //                            'title'    => '',
//                //                        //                            'url'      => '',
//                //                        //                            'children' => [
//                //                        //                                [
//                //                        //                                    'title' => '',
//                //                        //                                    'url'   => '',
//                //                        //                                ],
//                //                        //                            ],
//                //                        //                        ],
//                //                    ];
//                //                },
//            ]
//        ];
//    }

}
