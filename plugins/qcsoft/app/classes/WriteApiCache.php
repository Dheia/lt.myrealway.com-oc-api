<?php namespace Qcsoft\App\Classes;

use October\Rain\Support\Facades\Yaml;
use Qcsoft\App\Classes\Writeapicache\TypeHandler;
use Qcsoft\App\Models\Bundle;
use Qcsoft\App\Models\BundleProduct;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Entity;
use Qcsoft\App\Models\Custompage;
use Qcsoft\App\Models\Page;
use Qcsoft\App\Models\Product;

class WriteApiCache
{
    protected $config;

    public function __construct()
    {
        $this->config = Yaml::parseFile(__DIR__ . '/writeapicache/config.yaml');
    }

    public function write($maxTimeSeconds, $type = null, $contextStr = '')
    {
        $timeStart = microtime(true);

        $allTypes = $this->config['types'];
        $allTypeKeys = array_keys($allTypes);
        $type = $type ?: array_first($allTypeKeys);

        $type = 'entity';
        $type = 'filter';
        $type = 'filteroption';
        $type = 'layout';
        $type = 'page';
        $type = 'bundle';

        $this->makeTypeDir($type);

        $typeHandler = $this->makeTypeHandler($type, $allTypes[$type], $contextStr);

        for ($i = 0; $i < 100; $i++)
        {
            if ($typeHandler->isFinished())
            {
//                dd($typeHandler);
                $nextType = array_get($allTypeKeys, array_search($type, $allTypeKeys) + 1);
                dd($nextType);
//                if (!$type)
//                {
//                    return false;
//                }
//
//                $offset = 0;

                continue;
            }

            $result = $typeHandler->getCurrentBlock();

            foreach ($result as $key => $item)
            {
                file_put_contents(storage_path("apicache/$type/$key.json"), json_encode($item));
            }

            $typeHandler->moveToNextBlock();

            sleep(1);

            $timePassedTotal = microtime(true) - $timeStart;

            if (microtime(true) - $timeStart > $maxTimeSeconds)
            {
                return "$type/" . $typeHandler->contextToStr();
            }

            dump($typeHandler->context);
//
//            if ($prevOffset !== $offset)
//            {
//                $this->writeChunk($type, $result);
//            }
//            else
//            {
//                $type = $this->typesConfig->get($this->typesConfig->search($type) + 1);
//
//                if (!$type)
//                {
//                    return false;
//                }
//
//                $offset = 0;
//            }
//
//            $timePassedTotal = microtime(true) - $timeStart;
//
//            if ($timePassedTotal > $maxTimeSeconds)
//            {
//                return "$type/$offset";
//            }
        }

        return false;
    }

    public function lastWriteStatus()
    {
        return [
            'isFinished' => true
        ];
    }

    protected function makeTypeDir($type)
    {
        if (!\File::exists(storage_path("apicache/$type")))
        {
            \File::makeDirectory(storage_path("apicache/$type"), 511, true);
        }
    }

    protected function makeTypeHandler($type, $config, $contextStr): TypeHandler
    {
        $classname = __NAMESPACE__ . '\\writeapicache\\types\\' . \Str::studly($type);

        return $classname::fromContextStr($config, $contextStr);
    }

    protected function page($offset, $limit)
    {
        $items = Page::orderBy('id')->skip($offset)->take($limit)->get();

        $groups = $items->groupBy('owner_type_id');

        $resultList = [];

        foreach ($groups as $type_id => $items)
        {
//            $modelclass = Relation::getMorphedModel($type_id);
            $modelclass = Entity::classnameById($type_id);

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


    protected function bundle($offset, $limit)
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

    protected function bundle_product($offset, $limit)
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

    protected function catalogitem($offset, $limit)
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

    protected function custompage($offset, $limit)
    {
        $resultList = Custompage::orderBy('id')->skip($offset)->take($limit)
            ->get()
            ->keyBy('id');

        $offset += count($resultList);

        return $resultList;
    }

    protected function product($offset, $limit)
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
