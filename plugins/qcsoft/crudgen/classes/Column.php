<?php namespace Qcsoft\Crudgen\Classes;

class Column
{
    public static $dbmlToPhpTypes = [
        'integer' => 'int',
        'string'  => 'string',
        'text'    => 'string',
    ];

    /** @var object */
    public $column;

    /**
     * Column constructor.
     * @param object $column
     */
    public function __construct(object $column)
    {
        $this->column = $column;
    }

    public function getPhpType()
    {
        return static::$dbmlToPhpTypes[$this->column->type];
    }

}
