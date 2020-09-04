<?php

class ApiHandler
{
    public function handle($path, ApiStorage $storage, ApiResponse $response)
    {
        $regexp = "/^\/api\/(.+)\/([a-z0-9-]+)/";

        preg_match($regexp, $path, $matches);

        if (count($matches) !== 3)
        {
            return false;
        }

        array_shift($matches);

        list($method, $params) = $matches;

        if (!method_exists($this, $method))
        {
            return false;
        }

        if ($this->$method($params, $storage, $response) === false)
        {
            return false;
        }

        return true;
    }

    protected function page($params, ApiStorage $storage, ApiResponse $response)
    {
        if (!$page = $storage->getJson("page/$params"))
        {
            return false;
        }

        $owner = $storage->getJson("{$page->owner_type}/{$page->owner_id}");

        $response->add('page', $params, $page);

        $response->add($page->owner_type, $page->owner_id, $owner);

        $method = 'page' . ucfirst($page->owner_type);
        $this->$method($page, $owner, $storage, $response);
    }

    protected function pageGenericpage($page, $owner, ApiStorage $storage, ApiResponse $response)
    {
        switch ($owner->code)
        {
            case 'home':
            {
                $mainCarouselItems = [
                    [
                        'image'   => 'http://mrwshop.tk/themes/myrealway/assets/c2e7fed57db7aae72b638b962f9e297c.png',
                        'caption' => <<<EOT
                    <div class="carousel-labels">
                        <div class="carousel-label-1">
                            NATŪRALUS PLATAUS VARTOJIMO<br>
                            SPEKTRO <strong>ANTIPARAZITINIS</strong> KOMPLEKSAS
                        </div>
                        <ul class="carousel-label-2">
                            <div>- Profilaktika ir organizmo valymas</div>
                            <div>- <strong>100%</strong> augalų ekstraktai</div>
                        </ul>
                    </div>
EOT,
                    ], [
                        'image'   => 'http://mrwshop.tk/themes/myrealway/assets/c2e7fed57db7aae72b638b962f9e297c.png',
                        'caption' => <<<EOT
                    <div class="carousel-labels">
                        <div class="carousel-label-1">
                            NATŪRALUS PLATAUS VARTOJIMO<br>
                            SPEKTRO <strong>ANTIPARAZITINIS</strong> KOMPLEKSAS
                        </div>
                        <ul class="carousel-label-2">
                            <div>- Profilaktika ir organizmo valymas</div>
                            <div>- <strong>100%</strong> augalų ekstraktai</div>
                        </ul>
                    </div>
EOT,
                    ],
                ];

                $response->add('genericblock', 1, [
                    'genericpage_id' => $owner->id,
                    'component'      => 'SlideshowCarousel',
                    'props'          => [
                        'items' => $mainCarouselItems,
                    ],
                    'sort_order'     => 1,
                ]);

                $featuredProducts = [10, 11, 25, 26, 27, 28, 29, 30, 31];

                $response->add('genericblock', 2, [
                    'genericpage_id' => $owner->id,
                    'component'      => 'CatalogitemsCarousel',
                    'props'          => [
                        'ids'   => $featuredProducts,
                        'type'  => 'product',
                        'title' => 'Perkamiausi produktai',
                        'class' => 'mt-5',
                    ],
                    'sort_order'     => 2,
                ]);

                foreach ($featuredProducts as $id)
                {
                    $product = $storage->getJson("product/$id");
                    $catalogitem = $storage->getJson("catalogitem/{$product->catalogitem_id}");
                    $page = $storage->getJson("page/{$product->page_path}");

                    $response->add('product', $id, $product);
                    $response->add('catalogitem', $id, $catalogitem);
                    $response->add('page', $product->page_path, $page);

                    if ($catalogitem->main_image_id)
                    {
                        $this->addImage($response, $storage, 'md', $catalogitem->main_image_id);
                    }
                }

                $featuredBundles = [1000, 1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010];

                $response->add('genericblock', 3, [
                    'genericpage_id' => $owner->id,
                    'component'      => 'CatalogitemsCarousel',
                    'props'          => [
                        'ids'   => $featuredBundles,
                        'type'  => 'bundle',
                        'title' => 'Perkamiausios programos',
                        'class' => 'mt-5',
                    ],
                    'sort_order'     => 3,
                ]);

                foreach ($featuredBundles as $id)
                {
                    $bundle = $storage->getJson("bundle/$id");
                    $catalogitem = $storage->getJson("catalogitem/{$bundle->catalogitem_id}");
                    $page = $storage->getJson("page/{$bundle->page_path}");

                    $response->add('bundle', $id, $bundle);
                    $response->add('catalogitem', $id, $catalogitem);
                    $response->add('page', $bundle->page_path, $page);

                    if ($catalogitem->main_image_id)
                    {
                        $this->addImage($response, $storage, 'md', $catalogitem->main_image_id);
                    }
                }

                break;
            }
            default:
            {
            }
        }
    }

    protected function addImage($response, $storage, $size, $id)
    {
        $response->add('img', $id, [
            'id'  => $id,
            $size => $storage->get("image/$size/$id") ?: null
        ]);

//        if ($image = $storage->get("image/$size/$id"))
//        {
//            $response->add('img', $id, [
//                'id'  => $id,
//                $size => $image
//            ]);
//        }
    }

    protected function addResponseCatalogitemImage($response, $catalogitem, $attribute, $size)
    {

    }

    protected function pageProduct($page, $owner, ApiStorage $storage, ApiResponse $response)
    {

    }

    protected function pageBundle($page, $owner, ApiStorage $storage, ApiResponse $response)
    {

    }

}
