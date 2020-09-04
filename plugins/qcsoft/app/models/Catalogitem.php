<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CatalogitemBase;

class Catalogitem extends CatalogitemBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    protected static function boot()
    {
        parent::boot();

        static::extend(function ($model)
        {
            /** @var static $model */

            ////////////////////////////////////////////////////////////////////////////////
            /// Self-to-self through pivot (not yet supported bu modeler, so making it here)
            ////////////////////////////////////////////////////////////////////////////////
            $model->hasMany['catalogitem_relevant_catalogitems'] =
                [CatalogitemRelevantitem::class, 'key' => 'main_catalogitem_id', 'delete' => true];
            $model->hasMany['catalogitem_main_catalogitems'] =
                [CatalogitemRelevantitem::class, 'key' => 'relevant_catalogitem_id', 'delete' => true];
        });
    }

    public function saveFilteroptionsToRelation($requestedOptions)
    {
        $requestedOptionIds = array_filter(array_flatten($requestedOptions), function ($item)
        {
            return $item > 0;
        });

        $existingOptionIds = $this->catalogitem_filteroptions->pluck('filteroption_id')->toArray();

        $toDeleteIds = array_diff($existingOptionIds, $requestedOptionIds);
        $toInsertIds = array_diff($requestedOptionIds, $existingOptionIds);

        $toDeleteItems = CatalogitemFilteroption
            ::where('catalogitem_id', $this->id)
            ->whereIn('filteroption_id', $toDeleteIds)
            ->get();

        foreach ($toDeleteItems as $item)
        {
            $item->delete();
        }

        foreach ($toInsertIds as $filteroptionId)
        {
            $item = new CatalogitemFilteroption();
            $item->catalogitem_id = $this->id;
            $item->filteroption_id = $filteroptionId;

            $item->save();
        }
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
    public function getFilteroptionsAttribute()
    {
        return $this->catalogitem_filteroptions()->with([
            'filteroption' => function ($query)
            {
                $query->select(['id', 'filter_id']);
            }
        ])
            ->get()
            ->groupBy('filteroption.filter_id')
            ->map(function ($group)
            {
                return $group->pluck('filteroption_id');
            })
            ->toArray();
    }

    public function saveCustomergroupsToRelation($requestedGroupPrices)
    {
        $requestedGroupPrices = array_filter($requestedGroupPrices, function ($item)
        {
            return $item > 0;
        });

        $existingGroups = $this->catalogitem_customergroups;

        $toDeleteIds = array_diff($existingGroups->pluck('customergroup_id')->toArray(), array_keys($requestedGroupPrices));

        $toDeleteItems = CatalogitemCustomergroup
            ::where('catalogitem_id', $this->id)
            ->whereIn('customergroup_id', $toDeleteIds)
            ->get();

        foreach ($toDeleteItems as $item)
        {
            $item->delete();
        }

        foreach ($requestedGroupPrices as $customergroupId => $price)
        {
            if (!$itemToSave = $existingGroups->firstWhere('customergroup_id', $customergroupId))
            {
                $itemToSave = new CatalogitemCustomergroup();

                $itemToSave->catalogitem_id = $this->id;
                $itemToSave->customergroup_id = $customergroupId;
            }

            $itemToSave->price = $price;

            $itemToSave->save();
        }
    }

    public function getCustomergroupPriceAttribute()
    {
        return $this->catalogitem_customergroups
            ->pluck('price', 'customergroup_id')
            ->toArray();
    }

}
