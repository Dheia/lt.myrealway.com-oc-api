<?php namespace Qcsoft\App;

use Backend\Classes\Controller;
use October\Rain\Database\Relations\Relation;
use Qcsoft\App\Components\Cart;
use Qcsoft\App\Components\ProductList;
use Qcsoft\App\Formwidgets\BundleProductList;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Customer;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Product;
use RainLab\User\Models\User;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            ProductList::class => 'productList',
            Cart::class        => 'cart',
        ];
    }

    public function registerFormWidgets()
    {
        return [
            BundleProductList::class => 'bundleProductList',
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        // @todo deal with this somehow more professional >:/
        \Event::listen('cms.beforeRoute', function () use (&$pageObj, &$pageObjVarName)
        {
            \File::cleanDirectory(storage_path('cms/twig'));
        });

        Relation::morphMap([
            'bundle'  => Bundle::class,
            'product' => Product::class,
        ]);

        \Event::listen('qcsoft.cms.registerPageTypes', function ()
        {
            return [
                Product::class,
                Category::class,
            ];
        });

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
            $controller->addJs('/plugins/qcsoft/ocext/assets/js/dist/main.js');
            $controller->addJs('http://178.19.16.34:8080/main.js');
//            $controller->addJs('/plugins/qcsoft/app/assets/dist/main.js');
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
