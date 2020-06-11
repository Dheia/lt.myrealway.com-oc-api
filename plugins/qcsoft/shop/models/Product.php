<?php namespace Qcsoft\Shop\Models;

use October\Rain\Database\Collection;
use Qcsoft\Cms\Classes\PageModel;
use System\Models\File;
use Qcsoft\Shop\Modelsbase\ProductBase;

class Product extends ProductBase
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
            $model->hasMany['product_categories']['delete'] = true;
            $model->hasMany['product_bundles']['delete'] = true;
            $model->hasMany['product_filteroptions']['delete'] = true;

            ////////////////////////////////////////////////////////////////////////////////
            /// Sellable
            ////////////////////////////////////////////////////////////////////////////////
            $model->morphMany['cartitems'] = [Cartitem::class, 'name' => 'sellable'];

            ////////////////////////////////////////////////////////////////////////////////
            /// Convert octobercms backend checkboxlist values to related model records
            ////////////////////////////////////////////////////////////////////////////////
            $filterOptionsValue = null;

            $model->bindEvent('model.saveInternal', function () use ($model, &$filterOptionsValue) {

                $filterOptionsValue = array_get($model->attributes, 'filter_options', []);

                unset($model->attributes['filter_options']);
            });

            $model->bindEvent('model.afterSave', function () use ($model, &$filterOptionsValue) {

                $model->saveFilterOptionsToRelation($filterOptionsValue);
            });

            ////////////////////////////////////////////////////////////////////////////////
            /// Convert array of text inputs to related model records
            ////////////////////////////////////////////////////////////////////////////////
            $model->bindEvent('model.saveInternal', function () use ($model, &$customergroupPrices) {

                $customergroupPrices = array_get($model->attributes, 'customergroup_price', []);

                unset($model->attributes['customergroup_price']);
            });

            $model->bindEvent('model.afterSave', function () use ($model, &$customergroupPrices) {

                $model->saveCustomergroupPrices($customergroupPrices);
            });
        });
    }

    public function getDefaultPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setDefaultPriceAttribute($value)
    {
        return $this->attributes['default_price'] = $value * 100;
    }

    public function saveCustomergroupPrices($requestedItems)
    {
        /** @var Collection $existingItems */
        $existingItems = CustomergroupProduct::where('product_id', $this->id)->get();

        $requestedItems = collect($requestedItems)
            ->filter(function ($price) {
                return $price > 0;
            });

        $existingItems->keyBy('customergroup_id')
            ->diffKeys($requestedItems)
            ->each(function ($item) {
                $item->delete();
            });

        foreach ($requestedItems as $requestedId => $requestedPrice)
        {
            if (!$saveItem = $existingItems->firstWhere('customergroup_id', $requestedId))
            {
                $saveItem = new CustomergroupProduct();

                $saveItem->customergroup_id = $requestedId;
                $saveItem->product_id = $this->id;
            }

            $saveItem->price = $requestedPrice * 100;

            $saveItem->save();
        }
    }

    public function getCustomergroupPriceAttribute()
    {
        return $this->product_customergroups
            ->pluck('price', 'customergroup_id')
            ->map(function ($price) {
                return $price / 100;
            })
            ->toArray();
    }

    public function saveFilterOptionsToRelation($requestedOptions)
    {
        $requestedOptionIds = array_filter(array_flatten($requestedOptions), function ($item) {
            return $item > 0;
        });

        $existingOptionIds = $this->product_filteroptions->pluck('filteroption_id')->toArray();

        $toDeleteIds = array_diff($existingOptionIds, $requestedOptionIds);
        $toInsertIds = array_diff($requestedOptionIds, $existingOptionIds);

        // As of current design, this pivot table does not have any referencing tables,
        // so we don't have to delete records one-by-one in order to trigger Eloquent
        // to delete related records. Just use "low-level" where(...)->delete()
        FilteroptionProduct::where('product_id', $this->id)
            ->whereIn('filteroption_id', $toDeleteIds)
            ->delete();

        FilteroptionProduct::insert(array_map(function ($item) {
            return [
                'product_id'      => $this->id,
                'filteroption_id' => $item,
            ];
        }, $toInsertIds));
    }

    /**
     * Converts plain list of filteroption ids: [2, 3, 4, 6, 7,]
     * to a list grouped by filter id:
     * [
     *      1 => [2, 3, 4],
     *      5 => [6, 7]
     * ]
     * where 1 and 5 are filter ids
     * This is for backend checkboxlist field(s) controlling product filters
     * @return mixed
     */
    public function getFilterOptionsAttribute()
    {
        return $this->product_filteroptions()->with([
            'filteroption' => function ($query) {
                $query->select(['id', 'filter_id']);
            }
        ])
            ->get()
            ->groupBy('filteroption.filter_id')
            ->map(function ($group) {
                return $group->pluck('filteroption_id');
            })
            ->toArray();
    }

}
