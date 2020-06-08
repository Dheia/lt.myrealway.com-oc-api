<?php namespace Qcsoft\Myrealway;

use Cms\Classes\Controller;
use Cms\Classes\Page;
use Qcsoft\Myrealway\Classes\ImportOldSite;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
//        (new ImportOldSite())->import();

        \Event::listen('cms.beforeRoute', function () use (&$pageObj, &$pageObjVarName) {
            \File::cleanDirectory(storage_path('cms/twig'));
        });
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'headerMenu' => function () {
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
