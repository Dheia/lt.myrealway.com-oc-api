<?php namespace Qcsoft\App;

use Backend\Classes\Controller;
use Cms\Classes\Router;
use October\Rain\Database\Relations\Relation;
use Qcsoft\App\Components\Cart;
use Qcsoft\App\Components\CatalogitemList;
use Qcsoft\App\Components\ProductList;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Customer;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;
use RainLab\User\Models\User;
use System\Classes\PluginBase;

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
                'headerMenu' => function ()
                {
                    return [
                        [
                            'title'    => 'About us',
                            'url'      => '',
                            'children' => [
                                [
                                    'title' => 'Alliance',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Scientific potential',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Quality control',
                                    'url'   => '',
                                ],
                            ],
                        ],
                        /*[
                            'title'    => 'Bioregulators',
                            'url'      => '',
                            'children' => [
                                [
                                    'title' => 'About peptides',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'MRW bioregulators',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'MRW peptides',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Our advantages',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Select bioregulator',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Product catalog PDF',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Clinical trials PDF',
                                    'url'   => '',
                                ],
                            ],
                        ],
                        [
                            'title'    => 'Antiparasitic',
                            'url'      => '',
                            'children' => [],
                        ],
                        [
                            'title' => 'PROTECTION',
                            'url'   => '',
                        ],
                        [
                            'title'    => 'Practical use',
                            'url'      => '',
                            'children' => [
                                [
                                    'title' => 'Hair and Skin',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Preventive medicine',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Muscles and Ligaments',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Men\'s health',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Weight loss',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Women\'s health',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Practical protocols PDF',
                                    'url'   => '',
                                ],
                            ],
                        ],*/
                        [
                            'title'    => 'Blog',
                            'url'      => '',
                            'children' => [
                                [
                                    'title' => 'FAQ',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'News',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Events',
                                    'url'   => '',
                                ],
                                [
                                    'title' => 'Video',
                                    'url'   => '',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Partnership',
                            'url'   => '',
                        ],
                        [
                            'title' => 'E-SHOP',
                            'url'   => 'product-catalog',
                            'class' => 'e-shop'
                        ],
                        //                        [
                        //                            'title'    => '',
                        //                            'url'      => '',
                        //                            'children' => [
                        //                                [
                        //                                    'title' => '',
                        //                                    'url'   => '',
                        //                                ],
                        //                            ],
                        //                        ],
                    ];
                },
            ]
        ];
    }

}
