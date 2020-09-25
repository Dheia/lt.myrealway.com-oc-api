<?php namespace Qcsoft\App\Classes\Writeapicache\Storagetypes;

use Qcsoft\App\Classes\Writeapicache\TypeHandler;

abstract class OneByOneHandler extends TypeHandler
{
    public abstract function getTotalCount();

    public abstract function getBaseQuery();

    public abstract function getRange();

    public function getBaseList()
    {
        return $this->getBaseQuery()
            ->orderBy('id')
            ->skip($this->innerOffset)
            ->take($this->config['limit'])
            ->get();
    }

    public function current()
    {
        return $this->getRange();
    }

    public function next()
    {
        $this->innerOffset += $this->config['limit'];
    }

    public function key()
    {
        return $this->innerOffset < $this->count() ? $this->innerOffset : null;
    }

    public function valid()
    {
        return $this->innerOffset < $this->count();
    }

    public function rewind()
    {
        $this->innerOffset = 0;
    }

    public function count()
    {
        return $this->getTotalCount();
    }
}
