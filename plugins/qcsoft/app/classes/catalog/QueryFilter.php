<?php namespace Qcsoft\App\Classes\Catalog;

abstract class QueryFilter
{
    public abstract function applyToQuery($query);

}
