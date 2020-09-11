<?php namespace Qcsoft\App\Classes;

use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;
use Qcsoft\App\Models\Genericpage;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;

class WriteApiCache
{
    protected $types;

    protected $writeStartTime;

    public function __construct()
    {
        $this->types = collect(['bundle', 'catalogitem', 'filter', 'filteroption',
                                'genericpage', 'page', 'pagebypath', 'product']);
    }

    public function write($type, $offset)
    {
//        $this->types = collect(['page']);

        $this->writeStartTime = microtime(true);

        $limit = 100;

        if ($type === null)
        {
            $type = $this->types->first();
        }

        $offset = $offset ?: 0;

        for ($i = 0; $i < 1000; $i++)
        {
            $result = $this->$type($offset, $limit);

            if (count($result))
            {
                $this->writeChunk($type, $result, 0);

                $nextType = $type;
                $nextOffset = $offset + count($result);
            }
            else
            {
                $nextType = $this->types->get($this->types->search($type) + 1);
                $nextOffset = 0;
            }

            if (!$nextType)
            {
                return false;
            }

            $timePassedTotal = microtime(true) - $this->writeStartTime;

            if ($timePassedTotal > 5)
            {
                return "$nextType/$nextOffset";
            }

            $type = $nextType;
            $offset = $nextOffset;
        }

        return false;
    }

    protected function writeChunk($type, $data, $debug = 0)
    {
        if (!\File::exists(storage_path("apicache/$type")))
        {
            \File::makeDirectory(storage_path("apicache/$type"), 511, true);
        }

        if ($debug)
        {
            $debugTime = microtime(true) - $this->writeStartTime;

            echo "<pre>Time $debugTime\n\n";

            foreach ($data as $key => $item)
            {
                echo $key . ' => ' . json_encode($item, JSON_PRETTY_PRINT) . "\n\n";
            }

            echo '</pre>';

            die;
        }
        else
        {
            foreach ($data as $key => $item)
            {
                file_put_contents(storage_path("apicache/$type/{$key}.json"), json_encode($item));
            }
        }
    }

    protected function bundle($offset, $limit)
    {
        return Bundle::orderBy('id')->skip($offset)->take($limit)
            ->with([
                'catalogitem'     => function ($query)
                {
                    $query->select(['id', 'item_type', 'item_id']);
                },
                'page'            => function ($query)
                {
                    $query->select(['id', 'path', 'owner_type', 'owner_id']);
                },
                'bundle_products' => function ($query)
                {
//                $query->select(['id', 'path', 'owner_type', 'owner_id']);
                },
            ])
            ->get()
            ->map(function ($item)
            {
                $result = $item->toArray();

                $result['catalogitem_id'] = $result['catalogitem']['id'];
                unset($result['catalogitem']);

                $result['page_path'] = $result['page']['path'];
                unset($result['page']);

                $result['products'] = [];
                /** @var BundleProduct $bundle_product */
                foreach ($item->bundle_products as $bundle_product)
                {
                    $result['products'] [$bundle_product->product_id] = [$bundle_product->quantity, $bundle_product->price_override];
                }
                unset($result['bundle_products']);

                return $result;
            })
            ->keyBy('id');
    }

    protected function catalogitem($offset, $limit)
    {
        return Catalogitem::orderBy('id')->skip($offset)->take($limit)
            ->with([
                'main_image'                        => function ($query)
                {
//                    $query->select(['id', 'attachment_id']);
                },
                'catalogitem_relevant_catalogitems' => function ($query)
                {
                }
            ])
            ->get()
            ->map(function ($item)
            {
                $result = $item->toArray();
                $result['main_image_id'] = array_get($result['main_image'], 'id');
                unset($result['main_image']);

                $result['relevant_ids'] = $item->catalogitem_relevant_catalogitems
                    ->pluck('relevant_catalogitem_id')
                    ->toArray();
                unset($result['catalogitem_relevant_catalogitems']);

                return $result;
            })
            ->keyBy('id');
    }

    protected function filter($offset, $limit)
    {
        return Filter::orderBy('id')->skip($offset)->take($limit)
            ->select(['id', 'name', 'slug', 'sort_order'])
            ->get()
            ->keyBy('id');
    }

    protected function filteroption($offset, $limit)
    {
        return Filteroption::orderBy('id')->skip($offset)->take($limit)
            ->select(['id', 'filter_id', 'name', 'slug', 'sort_order'])
            ->get()
            ->keyBy('id');
    }

    protected function genericpage($offset, $limit)
    {
        return Genericpage::orderBy('id')->skip($offset)->take($limit)
            ->get()
            ->keyBy('id');
    }

    protected function page($offset, $limit)
    {
        $items = Page::orderBy('id')->skip($offset)->take($limit)->get();

        $groups = $items->groupBy('owner_type');

        $resultList = [];

        foreach ($groups as $type => $items)
        {
            $modelclass = [
                              'bundle'      => Bundle::class,
                              'genericpage' => Genericpage::class,
                              'product'     => Product::class,
                          ][$type];

            $require = $modelclass::getPageRequireEntities($items->pluck('owner_id'));

            foreach ($items as $item)
            {
                $result = $item->toArray();

                $result['require'] = array_get($require, $item->id, []);

                $resultList[$item->id] = $result;
            }
        }

        return $resultList;
    }

    protected function pagebypath($offset, $limit)
    {
        return Page::orderBy('id')->skip($offset)->take($limit)
            ->select(['id', 'path'])
            ->get()
            ->pluck('id', 'path')
            ->pipe(function ($result)
            {
                if (isset($result['/']))
                {
                    $result['home'] = $result['/'];
                    unset($result['/']);
                }

                return $result;
            });
    }


    protected function product($offset, $limit)
    {
        return Product::orderBy('id')->skip($offset)->take($limit)
            ->with([
                'catalogitem' => function ($query)
                {
                    $query->select(['id', 'item_type', 'item_id']);
                },
                'page'        => function ($query)
                {
                    $query->select(['id', 'owner_type', 'owner_id']);
                },
            ])
            ->get()
            ->map(function ($item)
            {
                $result = $item->toArray();
                $result['catalogitem_id'] = $result['catalogitem']['id'];
                $result['page_id'] = $result['page']['id'];
                unset($result['catalogitem']);
                unset($result['page']);

                return $result;
            })
            ->keyBy('id');
    }

}
