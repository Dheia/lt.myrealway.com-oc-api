<?php namespace Qcsoft\App\Classes;

use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Product;
use Qcsoft\Ocext\Classes\Util;

class ImportOldSite
{
    protected $htmlPath = 'plugins/qcsoft/app/classes/importoldsite';

    public function import()
    {
        \Debugbar::disable();

        $ocItems = \DB::connection('mrw_old')
            ->table('oc_product as p')
            ->join('oc_product_description as pd', 'p.product_id', '=', 'pd.product_id')
            ->get()
            ->map(function ($item)
            {
                $item->name = strtolower(trim($item->name));

                return $item;
            });

        $itemsFromOldSideHtml = \DB::table('qcsoft_importoldsite_productpage')->get()
            ->map(function ($item)
            {
                $item->product_name = strtolower(trim($item->product_name));

                return $item;
            });

        $values = [];

        foreach ($ocItems as $ocItem)
        {
            $desc = html_entity_decode($ocItem->description);

            $product = new Product();
            $product->catalogitem_name = $ocItem->name;
            $product->page_path = \Str::slug($ocItem->name);
            $product->product_code = $ocItem->model;
            $product->mini_desc = \Str::words(strip_tags($desc), 30);
            $product->description = $desc;
            $product->ingredients = html_entity_decode($ocItem->ingredients);
            $product->default_price = $ocItem->price;

            if ($oldSiteItem = $itemsFromOldSideHtml->firstWhere('product_name', $ocItem->name))
            {
                $productImageFileExtension = preg_replace('/^.+(\.\w{3})$/', '$1', $oldSiteItem->image_url);
                $productImageFilePath = base_path("$this->htmlPath/products/images/{$oldSiteItem->wwwpath}{$productImageFileExtension}");
                $product->catalogitem_main_image = $productImageFilePath;
            }

            $product->save();
        }

    }

//    protected $htmlPath = 'plugins/qcsoft/app/classes/importoldsite';
//
//    protected $baseMrwPath = 'https://en.myrealway.com/';
//
//    protected $tempTableProducts = 'qcsoft_importoldsite_productpage';
//    protected $tempTableCategories = 'qcsoft_importoldsite_categorypage';
//    protected $tempTablePivot = 'qcsoft_importoldsite_productpage_categorypage';
//
//    public function import()
//    {
//        Util::safedir($this->htmlPath);
//        Util::safedir($this->htmlPath . '/products');
//        Util::safedir($this->htmlPath . '/products/images');
//        Util::safedir($this->htmlPath . '/categories');
//
//        Util::tempTable($this->tempTableProducts, ['id', 'wwwpath', 'product_name', 'image_url', 'price_regular']);
//        Util::tempTable($this->tempTableCategories, ['id', 'wwwpath', 'name']);
//        Util::tempTable($this->tempTablePivot, ['id', 'productpage_id' => 'int', 'categorypage_id' => 'int']);
//
//        $this->parseCatalogSubpage(1);
//        $this->parseCatalogSubpage(2);
//        $this->parseCatalogSubpage(3);
//
//        $this->parseProductPages();
//
//        $this->downloadProductImages();
//
//        $this->parseCategoriesDropdown();
//
//        $this->parseCategoryPages();
//
//        $this->importProductsToShop();
//    }
//
//    protected function parseCatalogSubpage($pageNum)
//    {
//        $html = Util::cachedWebFile(
//            "{$this->baseMrwPath}product-catalog?path=&page=$pageNum",
//            "$this->htmlPath/page-$pageNum.html"
//        );
//
//        $doc = \phpQuery::newDocument($html);
//
//        foreach ($doc['.products .product-layout'] as $_product)
//        {
//            $product = pq($_product);
//            $linkElem = pq($product['.caption h4 a']);
//            $link = $linkElem->attr('href');
//
//            if (!starts_with($link, $this->baseMrwPath))
//            {
//                throw new \Exception('Something is wrong with the product path. Have to check it manually');
//            }
//
//            $parts = explode('/', $link);
//
//            \DB::table($this->tempTableProducts)->insert(['wwwpath' => array_last($parts)]);
//        }
//    }
//
//    protected function parseProductPages()
//    {
//        foreach (\DB::table($this->tempTableProducts)->get() as $product)
//        {
//            $html = Util::cachedWebFile(
//                "{$this->baseMrwPath}{$product->wwwpath}",
//                "$this->htmlPath/products/$product->wwwpath.html"
//            );
//
//            $doc = \phpQuery::newDocument($html);
//
//            if ($doc['.product-sumary h1']->length !== 1)
//            {
//                throw new \Exception('Invalid markup');
//            }
//
//            if ($doc['.product-thumbnails']->length !== 1)
//            {
//                throw new \Exception('Invalid markup');
//            }
//
//            $productName = pq($doc['.product-sumary h1'])->text();
//            $imageSrc = pq($doc['.product-thumbnails li a img'])->attr('src');
//            $priceRegular = pq($doc['#product .price-regular'])->text();
//            $priceRegular = preg_replace('/[^0-9.]/', '$1', $priceRegular);
//
//            \DB::table($this->tempTableProducts)
//                ->where('id', $product->id)
//                ->update([
//                    'product_name'  => $productName,
//                    'image_url'     => $imageSrc,
//                    'price_regular' => $priceRegular,
//                ]);
//        }
//    }
//
//    protected function parseCategoriesDropdown()
//    {
//        $html = Util::cachedWebFile(
//            "{$this->baseMrwPath}product-catalog?path=&page=1",
//            "$this->htmlPath/page-1.html"
//        );
//
//        $doc = \phpQuery::newDocument($html);
//
//        $options = $doc['select[name=category] option'];
//
//        foreach ($options as $_option)
//        {
//            $option = pq($_option);
//
//            if (strtolower($option->text()) === 'select category')
//            {
//                continue;
//            }
//
//            \DB::table($this->tempTableCategories)->insert([
//                'wwwpath' => $option->attr('value'),
//                'name'    => $option->text(),
//            ]);
//        }
//    }
//
//    protected function parseCategoryPages()
//    {
//        foreach (\DB::table($this->tempTableCategories)->get() as $categorypage)
//        {
//            $filepath = "$this->htmlPath/categories/" . \Str::slug($categorypage->name) . '.html';
//
//            $html = Util::cachedWebFile($categorypage->wwwpath, $filepath);
//
//            $doc = \phpQuery::newDocument($html);
//
//            foreach ($doc['.products .product-layout'] as $_product)
//            {
//                $product = pq($_product);
//                $linkElem = pq($product['.caption h4 a']);
//                $link = $linkElem->attr('href');
//
//                if (!starts_with($link, $this->baseMrwPath))
//                {
//                    throw new \Exception('Something is wrong with the product path. Have to check it manually');
//                }
//
//                $parts = explode('/', $link);
//
//                $productpagePath = array_last($parts);
//
//                if (!$productpage = \DB::table($this->tempTableProducts)
//                    ->where('wwwpath', $productpagePath)
//                    ->first())
//                {
//                    throw new \Exception("Product with path $productpagePath not found, this shouldn't've happened");
//                }
//
//                \DB::table($this->tempTablePivot)->insert([
//                    'productpage_id'  => $productpage->id,
//                    'categorypage_id' => $categorypage->id,
//                ]);
//            }
//        }
//    }
//
//    protected function downloadProductImages()
//    {
//        foreach (\DB::table($this->tempTableProducts)->get() as $product)
//        {
//            $fileExtension = preg_replace('/^.+(\.\w{3})$/', '$1', $product->image_url);
//
//            if (!in_array($fileExtension, ['.jpg', '.png']))
//            {
//                throw new \Exception('Invalid image file extension');
//            }
//        }
//
//        foreach (\DB::table($this->tempTableProducts)->get() as $product)
//        {
//            $fileExtension = preg_replace('/^.+(\.\w{3})$/', '$1', $product->image_url);
//
//            $filepath = "$this->htmlPath/products/images/{$product->wwwpath}{$fileExtension}";
//
//            Util::cachedWebFile($product->image_url, $filepath);
//        }
//    }
//
//    protected function importProductsToShop()
//    {
////        foreach (\DB::table($this->tempTableProducts)->get() as $productRaw)
////        {
////            $productImageFileExtension = preg_replace('/^.+(\.\w{3})$/', '$1', $productRaw->image_url);
////
////            $productImageFilePath = base_path("$this->htmlPath/products/images/{$productRaw->wwwpath}{$productImageFileExtension}");
////
////            $product = new Product();
////            $product->catalogitem_name = $productRaw->product_name;
////            $product->catalogitem_main_image = $productImageFilePath;
////            $product->default_price = $productRaw->price_regular;
////            $product->save();
////        }
////
////        foreach (\DB::table($this->tempTableCategories)->get() as $categoryRaw)
////        {
////            $category = new Category();
////            $category->name = $categoryRaw->name;
////            $category->save();
////        }
//    }
//
}
