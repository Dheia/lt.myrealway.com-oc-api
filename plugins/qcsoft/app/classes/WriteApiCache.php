<?php namespace Qcsoft\App\Classes;

use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;

class WriteApiCache
{
    public function write($type, $offset, $limit)
    {
        $types = collect(['bundle', 'catalogitem', 'product', 'page', 'genericpage']);

        if ($type === null)
        {
            $type = $types->first();
        }

        $method = 'write' . ucfirst($type) . 'Items';

        $processedCount = $this->$method($offset, $limit);

        if ($processedCount)
        {
            return $type . '/' . ($offset + $limit);
        }

        $index = $types->search($type);

        if ($nextType = $types->get($index + 1))
        {
            return "$nextType/0";
        }

        return false;
    }

    protected function writeBundleItems($offset, $limit)
    {
        if (!\File::exists(storage_path('apicache/bundle')))
        {
            \File::makeDirectory(storage_path('apicache/bundle'), 511, true);
        }

        $items = Bundle::with([
            'catalogitem' => function ($query)
            {
                $query->select(['id', 'item_type', 'item_id']);
            },
            'page'        => function ($query)
            {
                $query->select(['id', 'path', 'owner_type', 'owner_id']);
            },
        ])
            ->orderBy('id')
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($items as $item)
        {
            $result = $item->toArray();
            $result['catalogitem_id'] = $result['catalogitem']['id'];
            $result['page_path'] = $result['page']['path'];
            unset($result['catalogitem']);
            unset($result['page']);

            file_put_contents(storage_path("apicache/bundle/{$item->id}.json"), json_encode($result));
//            return;
        }

        return count($items);
    }

    protected function writeCatalogitemItems($offset, $limit)
    {
        if (!\File::exists(storage_path('apicache/catalogitem')))
        {
            \File::makeDirectory(storage_path('apicache/catalogitem'), 511, true);
        }

        $items = Catalogitem::with(['main_image' => function ($query)
        {
//            $query->select(['id','attachment_id']);
        }])
            ->orderBy('id')
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($items as $item)
        {
            $result = $item->toArray();
            $result['main_image_id'] = array_get($result['main_image'], 'id');
            unset($result['main_image']);

            file_put_contents(storage_path("apicache/catalogitem/{$item->id}.json"), json_encode($result));
//            return;
        }

        return count($items);
    }

    protected function writeGenericpageItems($offset, $limit)
    {
        if (!\File::exists(storage_path('apicache/genericpage')))
        {
            \File::makeDirectory(storage_path('apicache/genericpage'), 511, true);
        }

        $items = Genericpage
            ::orderBy('id')
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($items as $item)
        {
            file_put_contents(storage_path("apicache/genericpage/{$item->id}.json"), $item->toJson());
//            return;
        }

        return count($items);
    }

    protected function writePageItems($offset, $limit)
    {
        if (!\File::exists(storage_path('apicache/page')))
        {
            \File::makeDirectory(storage_path('apicache/page'), 511, true);
        }

        $items = Page
            ::orderBy('id')
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($items as $item)
        {
            $path = $item->path === '/' ? 'home' : $item->path;

            file_put_contents(storage_path("apicache/page/{$path}.json"), $item->toJson());
//            return;
        }

        return count($items);
    }

    protected function writeProductItems($offset, $limit)
    {
        if (!\File::exists(storage_path('apicache/product')))
        {
            \File::makeDirectory(storage_path('apicache/product'), 511, true);
        }

        $items = Product::with([
            'catalogitem' => function ($query)
            {
                $query->select(['id', 'item_type', 'item_id']);
            },
            'page'        => function ($query)
            {
                $query->select(['id', 'path', 'owner_type', 'owner_id']);
            },
        ])
            ->orderBy('id')
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($items as $item)
        {
            $result = $item->toArray();
            $result['catalogitem_id'] = $result['catalogitem']['id'];
            $result['page_path'] = $result['page']['path'];
            unset($result['catalogitem']);
            unset($result['page']);

            file_put_contents(storage_path("apicache/product/{$item->id}.json"), json_encode($result));
//            return;
        }

        return count($items);
    }

}
