<?php namespace Qcsoft\App\Dev;

use Illuminate\Support\Collection;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\BundleProductCustomergroup;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Layout;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;

class RandomBundles
{
    protected $allProducts;

    protected $layout_id;

    public function __construct()
    {
        $this->layout_id = Layout::cached()->firstWhere('code', 'bundle_page_default')->id;
    }

    public function generate($count = 1000)
    {
        \Debugbar::disable();

        foreach (collect(range(1, $count))->chunk(100) as $chunk)
        {
            $bundles = $this->insertItemsChunk(Bundle::class, $chunk);

            $catalogitems = $this->insertItemsChunk(Catalogitem::class, $bundles);

            foreach ($catalogitems as $item)
            {
                $bundle = $bundles->find($item->owner_id);

                $bundle->catalogitem = $item;
            }

            $pages = $this->insertItemsChunk(Page::class, $bundles);

            foreach ($pages as $page)
            {
                $bundle = $bundles->find($page->owner_id);

                $bundle->page = $page;
            }

            $this->allProducts = Product::all();

            $this->insertBundleChunkProducts($bundles);
        }
    }

    protected function insertBundleChunkProducts(Collection $from)
    {
        $values = [];

        foreach ($from as $bundle)
        {
            $productIds = $this->allProducts->pluck('id')->shuffle();

            foreach (range(1, rand(3, 7)) as $i)
            {
                $values[] = [
                    'bundle_id'  => $bundle->id,
                    'product_id' => $productIds[$i - 1],
                    'quantity'   => rand(1, 7),
                ];
            }
        }

        \DB::beginTransaction();

        $minId = BundleProduct::max('id') ?: 0;

        BundleProduct::insert($values);

        $inserted = BundleProduct
            ::where('id', '>', $minId)
            ->where('id', '<=', BundleProduct::max('id'))
            ->get();

        \DB::commit();

        return $inserted;
    }

    protected function insertItemsChunk($modelclass, Collection $from)
    {
        $method = 'makeRandom' . class_basename($modelclass);

        $records = $from->map(function ($item) use ($method)
        {
            return $this->$method($item);
        })
            ->toArray();

        \DB::beginTransaction();

        $minId = $modelclass::max('id') ?: 0;

        $modelclass::insert($records);

        $inserted = $modelclass
            ::where('id', '>', $minId)
            ->where('id', '<=', $modelclass::max('id'))
            ->get();

        \DB::commit();

        return $inserted;
    }

    protected function makeRandomBundle()
    {
        return [
            'description'   => $this->randomText(rand(50, 100), [5, 15]),
            'mini_desc'     => $this->randomText(rand(10, 20), [5, 15]),
            'default_price' => rand(500, 10000),
        ];
    }

    protected function makeRandomCatalogitem(Bundle $bundle)
    {
        return [
            'owner_type_id' => Bundle::$type_id,
            'owner_id'      => $bundle->id,
            'name'          => $this->randomText(rand(2, 5), [3, 10]),
        ];
    }

    protected function makeRandomPage(Bundle $bundle)
    {
        return [
            'layout_id' => $this->layout_id,
            'owner_id'  => $bundle->id,
            'path'      => \Str::slug($bundle->catalogitem->name) . '-' . $bundle->catalogitem->id,
        ];
    }

    protected function randomText($numWords, $wordLengthRange)
    {
        return collect(range(1, $numWords))
            ->map(function () use ($wordLengthRange)
            {
                return str_random(rand(...$wordLengthRange));
            })
            ->implode(' ');
    }

}
