<?php namespace Qcsoft\App\Classes;

use Backend\Widgets\Form;
use Backend\Widgets\Lists;
use October\Rain\Database\Builder;
use Qcsoft\App\Models\CatalogitemFilteroption;
use Qcsoft\App\Models\Customergroup;
use Qcsoft\App\Models\Filter;
use Qcsoft\App\Models\Filteroption;

class CatalogitemCustomergroupsBackend
{
    public function bindModelSave($item)
    {
        $item->bindEventOnce('model.saveInternal', function () use ($item, &$customergroup_prices)
        {
            $customergroup_prices = $item->attributes['catalogitem_customergroup_price'];

            unset($item->attributes['catalogitem_customergroup_price']);
        });

        $item->bindEventOnce('model.afterSave', function () use ($item, &$customergroup_prices)
        {
            $item->catalogitem->saveCustomergroupsToRelation($customergroup_prices);
        });
    }

    public function extendFormFields(Form $form)
    {
        $customergroups = Customergroup::all();

        foreach ($customergroups as $customergroup)
        {
            $form->tabs['fields']['catalogitem_customergroup_price[' . $customergroup->id . ']'] = [
                'label'               => 'Price for customer group "' . $customergroup->name . '" (' . $customergroup->id . ')',
                'attributes'          => [
                    'style' => 'position: absolute; left: 350px; top: 0; width: 150px'
                ],
                'containerAttributes' => [
                    'style' => 'clear: both; line-height: 32px'
                ],
                'type'                => 'number',
                'tab'                 => 'Price'
            ];
        }
    }

//    public function extendListColumns(Lists $lists)
//    {
//        $lists->addColumns(Filter::get()
//            ->mapWithKeys(function ($filter)
//            {
//                return [
//                    "filter_$filter->id" => [
//                        'label' => $filter->name,
//                        'type'  => 'text',
//                    ],
//                ];
//            })
//            ->toArray()
//        );
//    }
//
//    public function extendListQuery(Builder $query)
//    {
//        $filteroptionTable = (new Filteroption())->getTable();
//        $catalogitemFilteroptionTable = (new CatalogitemFilteroption())->getTable();
//
//        foreach (Filter::get() as $filter)
//        {
//            $query->addSelect(\DB::raw(<<<EOT
//        (
//            select group_concat(fo.name separator ', ')
//            from $catalogitemFilteroptionTable cifo
//                join $filteroptionTable fo on cifo.filteroption_id = fo.id
//            where cifo.catalogitem_id = cm_catalogitem.catalogitem_id
//            and fo.filter_id = $filter->id
//        ) as filter_$filter->id
//EOT
//            ));
//        }
//    }

}
