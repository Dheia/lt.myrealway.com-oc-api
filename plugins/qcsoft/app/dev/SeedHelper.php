<?php namespace Qcsoft\App\Dev;

use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\BundleProductCustomergroup;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\CatalogitemCategory;
use Qcsoft\App\Models\CatalogitemCustomergroup;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\CatalogitemRelevantitem;
use Qcsoft\App\Models\Category;
use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;
use System\Models\File;

class SeedHelper
{
    public function step1_cleanup()
    {
        Bundle::truncate();
        BundleProduct::truncate();
        BundleProductCustomergroup::truncate();
        Catalogitem::truncate();
        CatalogitemCategory::truncate();
        CatalogitemCustomergroup::truncate();
        CatalogitemFilteroption::truncate();
        CatalogitemRelevantitem::truncate();
        Category::truncate();
        Filter::truncate();
        Filteroption::truncate();
        Genericpage::truncate();
        Page::truncate();
        Product::truncate();
        File::truncate();
    }

    public function step2_seedGenericpages()
    {
        $data = [
            ['home', '/', 'Home', '<p>Home page</p>'],
            ['catalog', 'product-catalog', 'Product catalog', '""'],
            ['404', '404', '404', '""'],
            ['cart', 'cart', 'Cart', '""'],
            ['aboutus', 'about-us', 'About us', 'About us'],
            ['information', 'information', 'Information', 'Information'],
            ['contacts', 'contacts', 'Contacts', 'Contacts'],
        ];

        foreach ($data as $item)
        {
            $page = new Page();
            $page->owner = new Genericpage();
            $page->owner->name = $item[2];
            $page->owner->code = $item[0];
            $page->owner->content = $item[3];
            $page->path = $item[1];
            $page->owner->save();
            $page->save();
        }

    }

    public function step3_importOldSite()
    {
        (new ImportOldSite())->import();
    }

    public function step4_seedFilters()
    {
        $filtersData = [
            'Color'    => ['Red', 'Green', 'Blue', 'Yellow', 'Cyan', 'Magenta', 'Black', 'Grey', 'White'],
            'Size'     => ['Extra small', 'Small', 'Small-medium', 'Medium', 'Medium-large', 'Large', 'Extra large'],
            'Shape'    => ['Square', 'Triangle', 'Round', 'Oval', 'Pentagon', 'Hexagon'],
            'Material' => ['Plastic', 'Iron', 'Paper', 'Wood', 'Stone'],
        ];

        foreach ($filtersData as $name => $options)
        {
            $filter = new Filter();
            $filter->name = $name;
            $filter->slug = \Str::slug($name);

            $filter->save();

            foreach ($options as $option)
            {
                $filteroption = new Filteroption();
                $filteroption->filter_id = $filter->id;
                $filteroption->name = $option;
                $filteroption->slug = \Str::slug($option);

                $filteroption->save();
            }
        }

    }

    public function makeRandomFilterBindings($offset)
    {
        $limit = 500;

        $allFilteroptions = Filteroption::all();

        $catalogitems = Catalogitem::orderBy('id')->take($limit)->skip($offset)->get();

        if (!count($catalogitems))
        {
            return null;
        }

        $toInsert = [];

        foreach ($catalogitems as $catalogitem)
        {
            $filteroptionIds = $allFilteroptions->pluck('id')->shuffle();

            foreach (range(0, rand(10, 20)) as $i)
            {
                $toInsert[] = [
                    'catalogitem_id'  => $catalogitem->id,
                    'filteroption_id' => $filteroptionIds[$i],
                ];
            }
        }

        CatalogitemFilteroption::insert($toInsert);

        $offset += $limit;

        return $offset;
    }

    public function makeRandomCatalogitemRelevantItems($offset)
    {
        if (!$offset)
        {
            CatalogitemRelevantitem::truncate();
        }

        $limit = 200;

        $mainItems = Catalogitem::orderBy('id')->skip($offset)->limit($limit)->select(['id'])->pluck('id');

        if (!count($mainItems))
        {
            return null;
        }

        foreach ($mainItems as $mainItemId)
        {
            $relevantItems = Catalogitem::orderByRaw('rand()')->limit(rand(3, 10))->select(['id'])->pluck('id');

            foreach ($relevantItems as $relevantItemId)
            {
                CatalogitemRelevantitem::insert([
                    'main_catalogitem_id'     => $mainItemId,
                    'relevant_catalogitem_id' => $relevantItemId,
                ]);
            }
        }

        return $offset + count($mainItems);
    }

}
