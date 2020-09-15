<?php namespace Qcsoft\App\Classes;

use October\Rain\Database\Collection;
use October\Rain\Database\Relations\Relation;
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

    protected $defaultBlockSize = 100;

    protected $blockSize = [
        'bundle_product' => 5,
    ];

    public function __construct()
    {
        $this->types = collect(['bundle', 'bundle_product', 'catalogitem', 'filter', 'filteroption',
                                'genericpage', 'page', 'pagebypath', 'product']);
    }

    public function write($type, $offset)
    {
//        $this->types = collect(['page']);
//        $this->types = collect(['bundle_product']);

        $this->writeStartTime = microtime(true);

        if ($type === null)
        {
            $type = $this->types->first();
        }

        $offset = (int)$offset;

        for ($i = 0; $i < 1000; $i++)
        {
            $limit = array_get($this->blockSize, $type, $this->defaultBlockSize);

            $prevOffset = $offset;

            $result = $this->$type($offset, $limit);

            if ($prevOffset !== $offset)
            {
                $this->writeChunk($type, $result, 0);
            }
            else
            {
                $type = $this->types->get($this->types->search($type) + 1);

                if (!$type)
                {
                    return false;
                }

                $offset = 0;
            }

            $timePassedTotal = microtime(true) - $this->writeStartTime;

            if ($timePassedTotal > 5)
            {
                return "$type/$offset";
            }
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


    protected function page(&$offset, $limit)
    {
        $items = Page::orderBy('id')->skip($offset)->take($limit)->get();

        $groups = $items->groupBy('owner_type_id');

        $resultList = [];

        foreach ($groups as $type_id => $items)
        {
            $modelclass = Relation::getMorphedModel($type_id);

            $require = $modelclass::getPageRequireEntities($items->pluck('owner_id'));

            foreach ($items as $item)
            {
                $result = $item->toArray();

                $result['require'] = array_get($require, $item->id, []);

                $resultList[$item->id] = $result;
            }
        }
//dd($resultList);

        $offset += count($resultList);

        return $resultList;
    }


    protected function pagebypath(&$offset, $limit)
    {
        $resultList = Page::orderBy('id')->skip($offset)->take($limit)
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

        $offset += count($resultList);

        return $resultList;
    }


    protected function bundle(&$offset, $limit)
    {
        $resultList = Bundle::orderBy('id')->skip($offset)->take($limit)
            ->with([
                'catalogitem'     => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'page'            => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'bundle_products' => function ($query)
                {
                    $query->select(['id', 'bundle_id']);
                },
            ])
            ->get()
            ->map(function ($item)
            {
                $result = $item->toArray();

                $result['catalogitem_id'] = $result['catalogitem']['id'];
                unset($result['catalogitem']);

                $result['page_id'] = $result['page']['id'];
                unset($result['page']);

                $result['bundle_product'] = [];
                /** @var BundleProduct $bundle_product */
                foreach ($item->bundle_products as $bundle_product)
                {
                    $result['bundle_product'][] = $bundle_product->id;
                }
                unset($result['bundle_products']);

                return $result;
            })
            ->keyBy('id');

        $offset += count($resultList);

        return $resultList;
    }

    protected function bundle_product(&$offset, $limit)
    {
        $pageSize = 10;

        $maxId = BundleProduct::max('id');

        $fromId = $offset * $pageSize;

        $toId = ($offset + $limit) * $pageSize - 1;

        $items = BundleProduct::orderBy('id')
            ->where('id', '>=', $fromId)
            ->where('id', '<=', $toId)
            ->get()
            ->groupBy(function ($item) use ($pageSize)
            {
                return (int)($item->id / $pageSize);
            });

        if ($fromId <= $maxId)
        {
            $offset += $limit;
        }

        return $items;
    }

    protected function catalogitem(&$offset, $limit)
    {
        $resultList = Catalogitem::orderBy('id')->skip($offset)->take($limit)
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

        $offset += count($resultList);

        return $resultList;
    }

    protected function filter(&$offset, $limit)
    {
        $resultList = Filter::orderBy('id')->skip($offset)->take($limit)
            ->select(['id', 'name', 'slug', 'sort_order'])
            ->get()
            ->keyBy('id');

        $offset += count($resultList);

        return $resultList;
    }

    protected function filteroption(&$offset, $limit)
    {
        $resultList = Filteroption::orderBy('id')->skip($offset)->take($limit)
            ->select(['id', 'filter_id', 'name', 'slug', 'sort_order'])
            ->get()
            ->keyBy('id');

        $offset += count($resultList);

        return $resultList;
    }

    protected function genericpage(&$offset, $limit)
    {
        $resultList = Genericpage::orderBy('id')->skip($offset)->take($limit)
            ->get()
            ->keyBy('id');

        $offset += count($resultList);

        return $resultList;
    }

    protected function product(&$offset, $limit)
    {
        $resultList = Product::orderBy('id')->skip($offset)->take($limit)
            ->with([
                'catalogitem'     => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'page'            => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'product_bundles' => function ($query)
                {
                    $query->select(['id', 'product_id']);
                },
            ])
            ->get()
            ->map(function ($item)
            {
                $result = $item->toArray();

                $result['catalogitem_id'] = $result['catalogitem']['id'];
                unset($result['catalogitem']);

                $result['page_id'] = $result['page']['id'];
                unset($result['page']);

                $result['bundle_product'] = [];
                /** @var BundleProduct $bundle_product */
                foreach ($item->product_bundles as $bundle_product)
                {
                    $result['bundle_product'][] = $bundle_product->id;
                }
                unset($result['product_bundles']);

                return $result;
            })
            ->keyBy('id');

        $offset += count($resultList);

        return $resultList;
    }

}
