<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\BundleBase;
use System\Models\File;

class Bundle extends BundleBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public static function getPageRequireEntities($ids)
    {
        $bundles = static::query()
            ->whereIn('id', $ids)
            ->select(['id'])
            ->with([
                'catalogitem'                                                                   => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'page',
                'bundle_products'                                                               => function ($query)
                {
                    $query->select(['id', 'bundle_id', 'product_id', 'quantity', 'sort_order', 'price_override']);
                },
                'bundle_products.product'                                                       => function ($query)
                {
                    $query->select(['id']);
                },
                'bundle_products.product.page'                                                  => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'bundle_products.product.catalogitem'                                           => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'catalogitem.catalogitem_relevant_catalogitems',
                'catalogitem.catalogitem_relevant_catalogitems.relevant_catalogitem'            => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'catalogitem.catalogitem_relevant_catalogitems.relevant_catalogitem.owner'      => function ($query)
                {
                    $query->select(['id']);
                },
                'catalogitem.catalogitem_relevant_catalogitems.relevant_catalogitem.owner.page' => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
            ])
            ->get();
//        dd($bundles->toArray());
        $resultItems = [];

        foreach ($bundles as $bundle)
        {
            $result = [
                'bundle'         => [],
                'bundle_product' => [],
                'catalogitem'    => [],
                'image'          => [],
                'page'           => [],
                'product'        => [],
            ];

            $result['catalogitem'][] = $bundle->catalogitem->id;
            $result['bundle'][] = $bundle->id;

            foreach ($bundle->bundle_products as $bundle_product)
            {
                $result['bundle_product'][] = $bundle_product->id;
                $result['product'][] = $bundle_product->product->id;
                $result['catalogitem'][] = $bundle_product->product->catalogitem->id;
                $result['page'][] = $bundle_product->product->page->id;
            }
//            dd($bundle->toArray());

            $relevantItems = $bundle->catalogitem->catalogitem_relevant_catalogitems;

            foreach ($relevantItems as $relevantItem)
            {
//                dump($relevantItem->toArray());
                /** @var Catalogitem $relevantCatalogitem */
                $relevantCatalogitem = $relevantItem->relevant_catalogitem;
                $result['catalogitem'][] = $relevantCatalogitem->id;
                $result['page'][] = $relevantCatalogitem->owner->page->id;
                $result[Entity::typeById($relevantCatalogitem->owner_type_id)][] = $relevantCatalogitem->owner_id;
            }

            $resultItems[$bundle->page->id] = $result;
        }
//        dd($resultItems);
        return $resultItems;
    }

    public function getH1TitleAttribute()
    {
        return $this->page->custom_h1_title ?: $this->catalogitem->name;
    }

    public function getNameAttribute()
    {
        return $this->catalogitem_name;
    }

    public function setNameAttribute($value)
    {
        return $this->catalogitem_name = $value;
    }

    public function getCatalogitemPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setCatalogitemPriceAttribute($value)
    {
        return $this->attributes['catalogitem_price'] = $value * 100;
    }

    public function getSumPriceAttribute()
    {
        return $this->bundle_products->reduce(function ($prev, BundleProduct $bundleProduct)
        {
            return $prev + ($bundleProduct->product->default_price * $bundleProduct->quantity);
        }, 0);
    }

}
