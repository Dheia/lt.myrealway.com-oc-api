<?php namespace Qcsoft\App\Classes\Catalog;

class FilterOption
{
    /** @var OptionsFilter|null */
    public $filter;

    public $handler;

    /**
     * FilterOption constructor.
     * @param OptionsFilter|null $filter
     * @param $handler
     */
    public function __construct(?OptionsFilter $filter = null, $handler = null)
    {
        $this->filter = $filter;
        $this->handler = $handler;
    }

}
