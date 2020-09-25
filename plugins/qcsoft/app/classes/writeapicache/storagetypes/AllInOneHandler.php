<?php namespace Qcsoft\App\Classes\Writeapicache\Storagetypes;

use Qcsoft\App\Classes\Writeapicache\TypeHandler;

abstract class AllInOneHandler extends TypeHandler
{
    public abstract function getAll();

    public function current()
    {
        return $this->innerOffset === 0 ? $this->getAll() : null;
    }

    public function next()
    {
        $this->innerOffset++;
    }

    public function key()
    {
        return $this->innerOffset;
    }

    public function valid()
    {
        return $this->innerOffset === 0;
    }

    public function rewind()
    {
        $this->innerOffset = 0;
    }

    public function count()
    {
        return 1;
    }
}
