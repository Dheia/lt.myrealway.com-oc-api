<?php namespace Qcsoft\Crudgen\Classes;

use DbmlParser\Parser;

class DbmlSchema
{
    protected $tables;

    protected $relations;

    public function __construct($sourceDbmlFilePath)
    {
        $this->loadDbmlFile($sourceDbmlFilePath);
    }

    public function getTables()
    {
        return $this->tables;
    }

    public function getRelations()
    {
        return $this->relations;
    }

    protected function loadDbmlFile($sourceDbmlFilePath)
    {
        $parser = new Parser($sourceDbmlFilePath);

        $traits = [];

        foreach ($parser->tables as $table)
        {
            if (starts_with($table->name, '_'))
            {
                $traits[$table->name] = array_map([$this, 'prepareColumn'], $table->columns);
            }
        }

        $this->tables = [];

        foreach ($parser->tables as $table)
        {
            if (starts_with($table->name, '_'))
            {
                continue;
            }

            $columns = [];

            foreach ($table->columns as $srcColumn)
            {
                if (starts_with($srcColumn->type, '_'))
                {
                    foreach ($traits[$srcColumn->type] as $traitColumn)
                    {
                        $columns[] = $traitColumn;
                    }
                }
                else
                {
                    $columns[] = $this->prepareColumn($srcColumn);
                }
            }

            $this->tables[] = (object)[
                'name'    => $table->name,
                'columns' => $columns,
            ];
        }

        $this->relations = [];

        foreach ($parser->relations as $relation)
        {
            $relationTypeStr = $relation->type->__toString();

            if ($relationTypeStr === '>')
            {
                $this->relations[] = (object)[
                    'table'             => $relation->table->name,
                    'foreign_key'       => $relation->column->name,
                    'foreign_table'     => $relation->foreign_table->name,
                    'foreign_table_key' => $relation->foreign_column->name,
                ];
            }
            elseif ($relationTypeStr === '<')
            {
                $this->relations[] = (object)[
                    'table'             => $relation->foreign_table->name,
                    'foreign_key'       => $relation->foreign_column->name,
                    'foreign_table'     => $relation->table->name,
                    'foreign_table_key' => $relation->column->name,
                ];
            }
            else
            {
                throw new \Exception("Relation '$relationTypeStr' not implemented yet!");
            }
        }
    }

    /**
     * @param Parser\Column $in
     * @return object
     */
    protected function prepareColumn($in)
    {
        return (object)[
            'name'           => $in->name,
            'type'           => $in->type,
            'nullable'       => !$in->NotNull,
            'auto_increment' => !!$in->Increment,
            'pk'             => !!$in->PK,
        ];
    }
}
