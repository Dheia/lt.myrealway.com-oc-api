<?php namespace ApiHandler\Page;

use ApiResponse;
use ApiStorage;

class Genericpage
{
    public function handle($page, $owner, ApiStorage $storage, ApiResponse $response)
    {
        $this->{$owner->code}(...func_get_args());
    }

    protected function home($page, $owner, ApiStorage $storage, ApiResponse $response)
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
                \ApiHandler::addImage($response, $storage, 'md', $catalogitem->main_image_id);
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
                \ApiHandler::addImage($response, $storage, 'md', $catalogitem->main_image_id);
            }
        }
    }

    protected function catalog($page, $owner, ApiStorage $storage, ApiResponse $response)
    {
        $itemIds = [35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46];

        $response->add('genericblock', 4, [
            'genericpage_id' => $owner->id,
            'component'      => 'CatalogMain',
            'props'          => [
                'itemIds' => $itemIds,
            ],
            'sort_order'     => 1,
        ]);

        foreach ($itemIds as $id)
        {
            $catalogitem = $storage->getJson("catalogitem/$id");
            $owner = $storage->getJson("{$catalogitem->item_type}/{$catalogitem->item_id}");
            $page = $storage->getJson("page/{$owner->page_path}");

            $response->add($catalogitem->item_type, $catalogitem->item_id, $owner);
            $response->add('catalogitem', $id, $catalogitem);
            $response->add('page', $owner->page_path, $page);

            if ($catalogitem->main_image_id)
            {
                \ApiHandler::addImage($response, $storage, 'md', $catalogitem->main_image_id);
            }
        }
    }

    protected function aboutUs($page, $owner, ApiStorage $storage, ApiResponse $response)
    {
        $response->add('aaaaa', 'asdf', ['zxcv' => 123234]);

    }

}
