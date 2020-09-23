<?php namespace Qcsoft\App\Classes\Writeapicache;

abstract class TypeHandler
{
    /** @var array */
    public $config;

    /** @var \stdClass */
    public $context;

    public abstract function isFinished(): bool;

    public abstract function moveToNextBlock(): bool;

    public abstract function getStartContext(): \stdClass;

    public abstract function getCurrentBlock(): array;

    public function contextToStr(): string
    {
        return json_encode($this->context);
    }

    public static function fromContextStr(array $config, string $contextStr)
    {
        if (!$context = json_decode($contextStr))
        {
            $context = (array)[];
        }

        return new static($config, $context);
    }

    public function __construct(array $config, \stdClass $context)
    {
        $this->config = $config;
        $this->context = $context;
    }

}
