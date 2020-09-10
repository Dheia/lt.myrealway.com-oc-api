<?php namespace Qcsoft\App\Models;

use Illuminate\Support\Collection;
use Qcsoft\App\Classes\CatalogQuery;
use Qcsoft\App\Classes\OptionsFilter;
use Qcsoft\App\Modelsbase\FilterBase;

class Filter extends FilterBase
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
            /// Convert octobercms backend repeater value to related model records
            ////////////////////////////////////////////////////////////////////////////////
            $optionsRepeaterValue = null;

            $model->bindEvent('model.saveInternal', function () use ($model, &$optionsRepeaterValue)
            {

                $optionsRepeaterValue = array_get($model->attributes, 'options_repeater');

                unset($model->attributes['options_repeater']);
            });

            $model->bindEvent('model.afterSave', function () use ($model, &$optionsRepeaterValue)
            {
                $model->saveOptionsRepeaterToRelation($optionsRepeaterValue);
            });
        });
    }

    public function saveOptionsRepeaterToRelation($requestedOptions)
    {
        $existingOptions = $this->filteroptions;

        $requestedOptions = collect($requestedOptions);

        ////////////////////////////////////////////////////////////////////////////////
        /// Delete not existing items from database
        ////////////////////////////////////////////////////////////////////////////////
        foreach ($existingOptions as $existingOption)
        {
            // Can't delete them using where(...)->delete() because we need to trigger
            // the deletion of related models records

            /** @var Filteroption $existingOption */
            if (!$requestedOptions->firstWhere('id', $existingOption->id))
            {
                $existingOption->delete();
            }
        }

        ////////////////////////////////////////////////////////////////////////////////
        /// Add new items to database and update sort order
        ////////////////////////////////////////////////////////////////////////////////
        $currentSortOrder = 1;

        foreach ($requestedOptions as $requestedOption)
        {
            $saveItem = null;

            if (empty($requestedOption['id']))
            {
                $saveItem = new Filteroption();
            }
            else
            {
                /** @var Filteroption $existingOption */
                $existingOption = $existingOptions->firstWhere('id', $requestedOption['id']);

                if ($existingOption &&
                    (
                        $existingOption->name !== $requestedOption['name'] ||
                        $existingOption->sort_order != $currentSortOrder
                    )
                )
                {
                    $saveItem = $existingOption;
                }
            }

            if ($saveItem)
            {
                $saveItem->filter_id = $this->id;
                $saveItem->name = $requestedOption['name'];
                $saveItem->sort_order = $currentSortOrder;

                $saveItem->save();
            }

            $currentSortOrder++;
        }
    }

    public function getOptionsRepeaterAttribute()
    {
        return $this->filteroptions->sortBy('sort_order')->toArray();
    }

    public function applyToQuery($query, $options)
    {
        $optionIds = $options->pluck('handler.id')->implode(',');

        $query->whereRaw(<<<EOT
exists(
        select *
        from qcsoft_app_catalogitem_filteroption t
        where t.filteroption_id in ($optionIds)
          and t.catalogitem_id = qcsoft_app_catalogitem.id
    )
EOT
        );
    }

//    public function getSlugAttribute()
//    {
//        return \Str::slug($this->name);
//    }

}
