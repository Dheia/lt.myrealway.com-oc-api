<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\ProductBase;

class Product extends ProductBase
{
    public static function getPageRequireEntities($ids)
    {
        $products = static::query()
            ->whereIn('id', $ids)
            ->with(['catalogitem', 'page', 'catalogitem.catalogitem_relevant_catalogitems.relevant_catalogitem.item.page'])
            ->get();

        $resultItems = [];

        foreach ($products as $product)
        {
            $result = [
                'bundle'      => [],
                'catalogitem' => [],
                'page'        => [],
                'product'     => [],
            ];

            $result['catalogitem'][] = $product->catalogitem->id;
            $result['product'][] = $product->id;

            $relevantItems = $product->catalogitem->catalogitem_relevant_catalogitems;

            foreach ($relevantItems as $relevantItem)
            {
                /** @var Catalogitem $relevantCatalogitem */
                $relevantCatalogitem = $relevantItem->relevant_catalogitem;
                $result['catalogitem'][] = $relevantCatalogitem->item_id;
                $result['page'][] = $relevantCatalogitem->item->page->id;
                $result[$relevantCatalogitem->item_type][] = $relevantCatalogitem->item_id;
            }

            $resultItems[$product->page->id] = $result;
        }

        return $resultItems;
    }

//    public static function getPageRequireEntities($filter)
//    {
//        $query = static::query();
//
//        if (isset($filter['limit']))
//        {
//            $query = $query->orderBy('id')
//                ->skip(array_get($filter, 'offset', 0))
//                ->limit($filter['limit']);
//        }
//
//        $query->with(['catalogitem', 'page', 'catalogitem.catalogitem_relevant_catalogitems.relevant_catalogitem.item.page']);
//
//        $products = $query->get();
//
//        $resultItems = [];
//
//        foreach ($products as $product)
//        {
//            $result = [
//                'bundle'      => [],
//                'catalogitem' => [],
//                'page'        => [],
//                'product'     => [],
//            ];
//
//            $result['catalogitem'][] = $product->catalogitem->id;
//            $result['product'][] = $product->id;
//
//            $relevantItems = $product->catalogitem->catalogitem_relevant_catalogitems;
//
//            foreach ($relevantItems as $relevantItem)
//            {
//                /** @var Catalogitem $relevantCatalogitem */
//                $relevantCatalogitem = $relevantItem->relevant_catalogitem;
//                $result['catalogitem'][] = $relevantCatalogitem->item_id;
//                $result['page'][] = $relevantCatalogitem->item->page->id;
//                $result[$relevantCatalogitem->item_type][] = $relevantCatalogitem->item_id;
//            }
//
//            $resultItems[$product->page->id] = $result;
//        }
//
//        return $resultItems;
//    }

    public function getCatalogitemPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setCatalogitemPriceAttribute($value)
    {
        return $this->attributes['catalogitem_price'] = $value * 100;
    }



//    public function getH1TitleAttribute()
//    {
//        return $this->page->custom_h1_title ?: $this->catalogitem->name;
//    }
//
//    public function getPageApiData()
//    {
//        return [];
//    }
//
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::extend(function ($model)
//        {
//            /** @var static $model */
//
//            ////////////////////////////////////////////////////////////////////////////////
//            /// Auto delete related
//            ////////////////////////////////////////////////////////////////////////////////
//            $model->hasMany['product_categories']['delete'] = true;
//            $model->hasMany['product_bundles']['delete'] = true;
//            $model->hasMany['product_filteroptions']['delete'] = true;
//
//            $model->morphOne['catalogitem']['delete'] = true;
//
//            ////////////////////////////////////////////////////////////////////////////////
//            /// Sellable
//            ////////////////////////////////////////////////////////////////////////////////
//            $model->morphMany['cartitems'] = [Cartitem::class, 'name' => 'sellable'];
//
//            ////////////////////////////////////////////////////////////////////////////////
//            /// Convert array of text inputs to related model records
//            ////////////////////////////////////////////////////////////////////////////////
//            $model->bindEvent('model.saveInternal', function () use ($model, &$customergroupPrices)
//            {
//                unset($model->attributes['customergroup_price']);
//            });
//
//            $model->bindEvent('model.afterSave', function () use ($model, &$customergroupPrices)
//            {
//                if ($productRequest = \Request::input('Product'))
//                {
//                    $model->saveCustomergroupPrices(array_get($productRequest, 'customergroup_price', []));
//                }
//            });
//        });
//    }
//
//    public function getNameAttribute()
//    {
//        return $this->catalogitem_name;
//    }
//
//    public function setNameAttribute($value)
//    {
//        return $this->catalogitem_name = $value;
//    }
//
//    public function saveCustomergroupPrices($requestedItems)
//    {
//        /** @var Collection $existingItems */
//        $existingItems = CustomergroupProduct::where('product_id', $this->id)->get();
//
//        $requestedItems = collect($requestedItems)
//            ->filter(function ($price)
//            {
//                return $price > 0;
//            });
//
//        $existingItems->keyBy('customergroup_id')
//            ->diffKeys($requestedItems)
//            ->each(function ($item)
//            {
//                $item->delete();
//            });
//
//        foreach ($requestedItems as $requestedId => $requestedPrice)
//        {
//            if (!$saveItem = $existingItems->firstWhere('customergroup_id', $requestedId))
//            {
//                $saveItem = new CustomergroupProduct();
//
//                $saveItem->customergroup_id = $requestedId;
//                $saveItem->product_id = $this->id;
//            }
//
//            $saveItem->price = $requestedPrice * 100;
//
//            $saveItem->save();
//        }
//    }
//
//    public function getCustomergroupPriceAttribute()
//    {
//        return $this->product_customergroups
//            ->pluck('price', 'customergroup_id')
//            ->map(function ($price)
//            {
//                return $price / 100;
//            })
//            ->toArray();
//    }

}
