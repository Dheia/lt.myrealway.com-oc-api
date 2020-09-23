<?php namespace Qcsoft\App\Classes\Writeapicache;

abstract class TypeHandlerAll extends TypeHandler
{
    public abstract function getAll();

    public function isFinished(): bool
    {
        return true;
    }

    public function moveToNextBlock(): bool
    {
        return false;
    }

    public function getStartContext(): \stdClass
    {
        return (object)[];
    }

    public function getCurrentBlock(): array
    {
        return ['all' => $this->getAll()];
    }

}
