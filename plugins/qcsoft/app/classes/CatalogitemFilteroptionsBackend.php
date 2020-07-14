<?php namespace Qcsoft\App\Classes;

use Backend\Widgets\Form;
use Backend\Widgets\Lists;
use October\Rain\Database\Builder;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;

class CatalogitemFilteroptionsBackend
{
    public function bindModelSave($item)
    {
        $item->bindEventOnce('model.saveInternal', function () use ($item, &$filteroptions)
        {
            $filteroptions = $item->attributes['catalogitem_filteroptions'];

            unset($item->attributes['catalogitem_filteroptions']);
        });

        $item->bindEventOnce('model.afterSave', function () use ($item, &$filteroptions)
        {
            $item->catalogitem->saveFilterOptionsToRelation($filteroptions);
        });
    }

    public function extendFormFields(Form $form)
    {
        $filters = Filter::with('filteroptions')->get();

        foreach ($filters as $filter)
        {
            $form->tabs['fields']['catalogitem_filteroptions[' . $filter->id . ']'] = [
                'label'       => $filter->name . ' (' . $filter->id . ')',
                'span'        => 'auto',
                'type'        => 'checkboxlist',
                'quickselect' => true,
                'options'     => $filter->filteroptions
                    ->sortBy('sort_order')
                    ->map(function ($item)
                    {
                        $item->name = $item->name . ' (' . $item->id . ')';
                        return $item;
                    })
                    ->lists('name', 'id'),
                'tab'         => 'Filters'
            ];
        }
    }

    public function extendListColumns(Lists $lists)
    {
        $lists->addColumns(Filter::get()
            ->mapWithKeys(function ($filter)
            {
                return [
                    "filter_$filter->id" => [
                        'label' => $filter->name,
                        'type'  => 'text',
                    ],
                ];
            })
            ->toArray()
        );
    }

    public function extendListQuery(Builder $query)
    {
        $filteroptionTable = (new Filteroption())->getTable();
        $catalogitemFilteroptionTable = (new CatalogitemFilteroption())->getTable();

        foreach (Filter::get() as $filter)
        {
            $query->addSelect(\DB::raw(<<<EOT
        (
            select group_concat(fo.name separator ', ')
            from $catalogitemFilteroptionTable cifo
                join $filteroptionTable fo on cifo.filteroption_id = fo.id
            where cifo.catalogitem_id = cm_catalogitem.catalogitem_id
            and fo.filter_id = $filter->id
        ) as filter_$filter->id
EOT
            ));
        }
    }

}
