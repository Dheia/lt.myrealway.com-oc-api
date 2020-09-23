<?php namespace Qcsoft\App\Classes\Writeapicache;

abstract class TypeHandlerOneByOne extends TypeHandler
{
    public abstract function getTotalCount();

    public abstract function getBaseQuery();

    public abstract function getRange();

    public function getStartContext(): \stdClass
    {
        return (object)['offset' => 0];
    }

    public function isFinished(): bool
    {
        return $this->context->offset >= $this->getTotalCount();
    }

    public function moveToNextBlock(): bool
    {
        $count = $this->getTotalCount();
        $nextOffset = $this->context->offset + $this->config['limit'];

        if ($nextOffset >= $count)
        {
            return false;
        }

        $this->context->offset = $nextOffset;

        return true;
    }

    public function getCurrentBlock(): array
    {
        return $this->getRange();
    }

    public function getBaseList()
    {
        return $this->getBaseQuery()
            ->orderBy('id')
            ->skip($this->context->offset)
            ->take($this->config['limit'])
            ->get();
    }

}
