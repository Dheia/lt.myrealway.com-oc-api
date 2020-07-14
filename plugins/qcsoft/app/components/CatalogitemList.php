<?php namespace Qcsoft\App\Components;

use Cms\Classes\ComponentBase;
use Qcsoft\App\Models\Catalogitem;
use Qcsoft\App\Models\Filter;

class CatalogitemList extends ComponentBase
{
    /** @var \October\Rain\Support\Collection */
    protected static $selectedFilterOptions;

    /** @var \October\Rain\Database\Collection */
    protected static $allowedFiltersSlugMap;

    /** @var \October\Rain\Database\Collection */
    protected static $allFilters;

    /** @var \October\Rain\Database\Collection */
    protected static $catalogitemList;

    /** @var int */
    protected static $filteredCount;

    /** @var int */
    public $perPage = 12;

    public function componentDetails()
    {
        return [
            'name' => 'CatalogitemList',
        ];
    }

    public function onLoadCatalogitemListPage()
    {
        return [
            'itemsHtml'     => $this->renderPartial('catalogitem-list/catalogitem-list-items'),
            'filteredCount' => $this->getFilteredCount(),
        ];
    }

    public function getCatalogitemList()
    {
        $this->loadCatalogitemListAndCount();

        return static::$catalogitemList;
    }

    public function getFilteredCount()
    {
        $this->loadCatalogitemListAndCount();

        return static::$filteredCount;
    }

    protected function loadCatalogitemListAndCount()
    {
        if (!static::$catalogitemList)
        {
            $query = Catalogitem::with('main_image');

            $pageNum = \Request::input('page');

            $selectedFilterOptions = $this->getSelectedFilterOptions();

            foreach ($this->getAllFilters() as $filter)
            {
                if ($option = $selectedFilterOptions->firstWhere('filter_id', $filter->id))
                {
                    $query->whereHas('catalogitem_filteroptions', function ($query) use ($option) {
                        $query->where('filteroption_id', $option->id);
                    });
                }
            }

            static::$filteredCount = $query->count();

            static::$catalogitemList = $query
                ->orderBy('id')
                ->skip(($pageNum - 1) * $this->perPage)
                ->limit($this->perPage)
                ->get();
        }
    }

    public function isFilterOptionSelected($optionId)
    {
        return !!$this->getSelectedFilterOptions()->firstWhere('id', $optionId);
    }

    public function getAllFilters()
    {
        if (!static::$allFilters)
        {
            static::$allFilters = Filter::with('filteroptions')->get();
        }

        return static::$allFilters;
    }

    public function getAllowedFiltersSlugMap()
    {
        if (!static::$allowedFiltersSlugMap)
        {
            static::$allowedFiltersSlugMap = $this->getAllFilters()
                ->mapWithKeys(function ($filter) {
                    return [$filter->slug => $filter->filteroptions->pluck('slug')];
                });
        }

        return static::$allowedFiltersSlugMap;
    }

    public function getSelectedFilterOptions()
    {
        if (!static::$selectedFilterOptions)
        {
            $slugMap = $this->getAllFilters()
                ->mapWithKeys(function ($filter) {
                    return [$filter->slug => $filter->filteroptions->keyBy('slug')];
                });

            static::$selectedFilterOptions = collect(\Request::query())
                ->map(function ($value, $key) use ($slugMap) {
                    return array_get($slugMap, "$key.$value");
                })
                ->filter()
                ->values();
        }

        return static::$selectedFilterOptions;
    }
}
