<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\ProductBase;

class Product extends ProductBase
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);


//        $result = new Product();
//\Debugbar::info($this->exists);
//\Debugbar::info($this->catalogitem);
//        $this->catalogitem = new Catalogitem();

//        return $result;
    }

    protected static function boot()
    {
        parent::boot();

        static::extend(function ($model)
        {
            /** @var static $model */

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
        });
    }

    public function getNameAttribute()
    {
        return $this->catalogitem_name;
    }

    public function setNameAttribute($value)
    {
        return $this->catalogitem_name = $value;
    }

    public function getDefaultPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setDefaultPriceAttribute($value)
    {
        return $this->attributes['default_price'] = $value * 100;
    }

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
