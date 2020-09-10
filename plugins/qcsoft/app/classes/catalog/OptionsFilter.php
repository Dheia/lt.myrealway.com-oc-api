<?php namespace Qcsoft\App\Classes\Catalog;

use October\Rain\Database\Builder;

class OptionsFilter extends QueryFilter
{
    public $options;

    public $handler;

    public function __construct($handler = null)
    {
        $this->options = collect();
        $this->handler = $handler;
    }

    public function addOption($handler, $key = null)
    {
        if ($key !== null)
        {
            $this->options[$key] = new FilterOption($this, $handler);
        }
        else
        {
            $this->options[] = new FilterOption($this, $handler);
        }
    }

    /**
     * @param Builder $query
     */
    public function applyToQuery($query)
    {
        $this->handler->applyToQuery($query, $this->options);
    }
}
