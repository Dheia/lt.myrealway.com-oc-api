<?php namespace Qcsoft\App\Classes\Catalog;

use Qcsoft\App\Models\Catalogitem;

class CatalogQuery
{
    public $search;

    public $filters;

    public $sorting;

    public $skip;

    public $limit;

    public function __construct()
    {
        $this->search = [];
        $this->filters = [];
        $this->sorting = [];
        $this->skip = 0;
        $this->limit = 12;
    }

    public function addOptionsFilter($handler, $key = null)
    {
        return $this->addFilter(new OptionsFilter($handler), $key);
    }

    protected function addFilter($filter, $key = null)
    {
        if ($key !== null)
        {
            $this->filters[$key] = $filter;
        }
        else
        {
            $this->filters[] = $filter;
        }

        return $filter;
    }

    public function setSorting($column, $direction = 'asc')
    {
        $this->sorting = compact('column', 'direction');
    }

    public function setLimit($limit, $skip = null)
    {
        $this->limit = $limit;

        if ($skip !== null)
        {
            $this->skip = $skip;
        }
    }

    public function setSkip($skip)
    {
        $this->skip = $skip;
    }

    public function get()
    {
        $query = Catalogitem::query();

        /** @var QueryFilter $filter */
        foreach ($this->filters as $filter)
        {
            $filter->applyToQuery($query);
        }

        $query->orderBy($this->sorting['column'], $this->sorting['direction']);

        $query->skip($this->skip);

        $query->limit($this->limit);

        $query->select('id');

        return $query->pluck('id');
    }

}
