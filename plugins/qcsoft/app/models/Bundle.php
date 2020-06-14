<?php namespace Qcsoft\App\Models;

use Qcsoft\Cms\Classes\PageModel;
use Qcsoft\App\Modelsbase\BundleBase;
use System\Models\File;

class Bundle extends BundleBase
{
    use PageModel;

    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public $attachOne = [
        'main_image' => [File::class],
    ];

    protected static function boot()
    {
        parent::boot();

        static::extend(function ($model) {

            /** @var static $model */

            ////////////////////////////////////////////////////////////////////////////////
            /// Auto delete related
            ////////////////////////////////////////////////////////////////////////////////
            $model->hasMany['bundle_products']['delete'] = true;

            ////////////////////////////////////////////////////////////////////////////////
            /// Convert octobercms backend checkboxlist values to related model records
            ////////////////////////////////////////////////////////////////////////////////

            $model->bindEvent('model.saveInternal', function () use ($model) {

                unset($model->attributes['bundle_products_json']);
            });

            $model->bindEvent('model.afterSave', function () use ($model) {

                if ($bundleRequest = \Request::input('Bundle'))
                {
                    $model->saveBundleProductsToRelation(array_get($bundleRequest, 'bundle_products_json', []));
                }
            });

        });
    }

    public function saveBundleProductsToRelation($requestedItems)
    {
        $existingItems = $this->bundle_products;

        $requestedItems = collect($requestedItems);

        $existingItems->keyBy('product_id')
            ->diffKeys($requestedItems)
            ->each(function ($item) {
                $item->delete();
            });

        $sort_order = 1;

        foreach ($requestedItems as $product_id => $item)
        {
            $item = json_decode($item);

            if (!$saveItem = $existingItems->firstWhere('product_id', $product_id))
            {
                $saveItem = new BundleProduct();

                $saveItem->bundle_id = $this->id;
                $saveItem->product_id = $product_id;
            }

            $saveItem->quantity = $item->quantity;
            $saveItem->sort_order = $sort_order;
            $saveItem->price_override = $item->price_override;

            $saveItem->save();

            $sort_order++;
        }
    }

    public function setBundleProductsJsonAttribute($value)
    {
        $this->attributes['bundle_products_json'] = $value;
    }

    public function getBundleProductsJsonAttribute()
    {
        return $this->bundle_products()
            ->with(['product', 'product.main_image'])
            ->get()
            ->sortBy('sort_order')
            ->values()
            ->map(function ($item) {

                $main_image = $item->product->main_image->getThumb(120, 120, ['mode' => 'crop']);

                $product = $item->product->toArray();

                $product['main_image'] = $main_image;

                $result = $item->toArray();

                $result['product'] = $product;

                $result['customergroups'] = [];

                return $result;

            })->toJson();
    }

    public function getDefaultPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setDefaultPriceAttribute($value)
    {
        return $this->attributes['default_price'] = $value * 100;
    }

}
