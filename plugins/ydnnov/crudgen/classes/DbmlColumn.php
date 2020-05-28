<?php namespace Ydnnov\Crudgen\Classes;

use DbmlParser\Parser\Column;

class DbmlColumn
{
    public static $dbmlToPhpTypes = [
        'int'     => 'int',
        'varchar' => 'string',
        'text'    => 'string',
    ];

    /** @var Column */
    public $column;

    /**
     * DbmlColumn constructor.
     * @param Column $column
     */
    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function getPhpType()
    {
        return static::$dbmlToPhpTypes[$this->column->type];
    }

}
