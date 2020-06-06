<?php namespace Qcsoft\Myrealway\Classes;

use October\Rain\Database\Schema\Blueprint;
use Qcsoft\Shop\Models\FilteroptionProduct;
use Qcsoft\Shop\Models\Product;

class ImportOldSite
{
    protected $htmlPath = 'qcsoft/myrealway/classes/importoldsite';

    protected $baseMrwPath = 'https://en.myrealway.com/';

    protected $tempTableName = 'qcsoft_importoldsite_productpage';

    public function import()
    {
//        $this->parseCatalogPages();
//        $this->downloadProductPages();
//        $this->fillProductDetails();
//        $this->downloadProductImages();
//        $this->importProductsToShop();
//        $this->setResultProductPaths();
//        $this->makeRandomGenderFilterBindings();
    }

    /**
     * This method here is just for convenience, it should be removed sometime
     */
    protected function makeRandomGenderFilterBindings()
    {
        foreach (Product::all() as $product)
        {
            $filterOptions = [
                                 ['male'],
                                 ['female'],
                                 ['male', 'female'],
                             ][rand(0, 2)];

            foreach ($filterOptions as $i => $foption)
            {
                $fop = new FilteroptionProduct();
                $fop->product_id = $product->id;
                $fop->filteroption_id = $i + 1;
                $fop->save();
            }
        }
    }

    protected function setResultProductPaths()
    {
        foreach (Product::all() as $product)
        {
            $product->path = \Str::slug($product->name);

            $product->save();
        }
    }

    protected function importProductsToShop()
    {
        foreach (\DB::table($this->tempTableName)->get() as $productRaw)
        {
            $productImageFileExtension = preg_replace('/^.+(\.\w{3})$/', '$1', $productRaw->image_url);

            $productImageFilePath = plugins_path("$this->htmlPath/products/images/{$productRaw->wwwpath}{$productImageFileExtension}");

            $product = new Product();
            $product->name = $productRaw->product_name;
            $product->main_image = $productImageFilePath;
            $product->save();
        }
    }

    protected function downloadProductImages()
    {
        foreach (\DB::table($this->tempTableName)->get() as $product)
        {
            $fileExtension = preg_replace('/^.+(\.\w{3})$/', '$1', $product->image_url);

            if (!in_array($fileExtension, ['.jpg', '.png']))
            {
                throw new \Exception('Invalid image file extension');
            }
        }

        foreach (\DB::table($this->tempTableName)->get() as $product)
        {
            $ch = curl_init($product->image_url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $fileExtension = preg_replace('/^.+(\.\w{3})$/', '$1', $product->image_url);

            $filepath = plugins_path("$this->htmlPath/products/images/{$product->wwwpath}{$fileExtension}");

            file_put_contents($filepath, $result);
        }
    }

    protected function fillProductDetails()
    {
        foreach (\DB::table($this->tempTableName)->get() as $product)
        {
            $filepath = plugins_path("$this->htmlPath/products/$product->wwwpath.html");

            $doc = \phpQuery::newDocument(file_get_contents($filepath));

            if ($doc['.product-sumary h1']->length !== 1)
            {
                throw new \Exception('Invalid markup');
            }

            if ($doc['.product-thumbnails']->length !== 1)
            {
                throw new \Exception('Invalid markup');
            }

            $productName = pq($doc['.product-sumary h1'])->text();
            $imageSrc = pq($doc['.product-thumbnails li a img'])->attr('src');

            \DB::table($this->tempTableName)
                ->where('id', $product->id)
                ->update([
                    'product_name' => $productName,
                    'image_url'    => $imageSrc,
                ]);
        }
    }

    protected function downloadProductPages()
    {
        foreach (\DB::table($this->tempTableName)->get() as $product)
        {
            $ch = curl_init("{$this->baseMrwPath}{$product->wwwpath}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $filepath = plugins_path("$this->htmlPath/products/$product->wwwpath.html");
            file_put_contents($filepath, $result);
        }
    }

    protected function parseCatalogPages()
    {
        if (!\Schema::hasTable($this->tempTableName))
        {
            \Schema::create($this->tempTableName, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('wwwpath');
                $table->string('product_name');
                $table->string('image_url');
            });
        }

        $this->parseCatalogPage(1);
        $this->parseCatalogPage(2);
        $this->parseCatalogPage(3);
    }

    protected function parseCatalogPage($pageNum)
    {
        $filepath = plugins_path("$this->htmlPath/page-$pageNum.html");

        $doc = \phpQuery::newDocument(file_get_contents($filepath));

        foreach ($doc['.products .product-layout'] as $_product)
        {
            $product = pq($_product);
            $linkElem = pq($product['.caption h4 a']);
            $link = $linkElem->attr('href');

            if (!starts_with($link, $this->baseMrwPath))
            {
                throw new \Exception('Something is wrong with the product path. Have to check it manually');
            }

            $parts = explode('/', $link);

            \DB::table($this->tempTableName)
                ->insert([
                    'wwwpath'      => array_last($parts),
                    'product_name' => '',
                    'image_url'    => '',
                ]);
        }
    }
}
