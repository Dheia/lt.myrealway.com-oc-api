<?php namespace Qcsoft\App\Classes\Writeapicache;

abstract class TypeHandler implements \Iterator, \Countable
{
    /** @var array */
    public $config;

    /** @var int */
    public $innerOffset;

    public function __construct(array $config, int $innerOffset)
    {
        $this->config = $config;
        $this->innerOffset = $innerOffset;
    }
}
