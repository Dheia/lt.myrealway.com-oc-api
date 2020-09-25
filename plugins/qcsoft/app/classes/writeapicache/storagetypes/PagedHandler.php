<?php namespace Qcsoft\App\Classes\Writeapicache;

use Qcsoft\App\Classes\Writeapicache\Storagetypes\PagedIterator;

class PagedHandler extends TypeHandler
{
    public function getIterator()
    {
        return new PagedIterator($this);
    }
}
