<?php namespace Qcsoft\App\Classes\Writeapicache\Types;

use Qcsoft\App\Classes\Writeapicache\TypeHandlerAll;

class Layout extends TypeHandlerAll
{
    public function getById($id)
    {
        return \Qcsoft\App\Models\Layout
            ::select($this->getSelectColumns())
            ->where('id', $id)
            ->first()
            ->toArray();
    }

    public function getAll()
    {
        return \Qcsoft\App\Models\Layout
            ::select($this->getSelectColumns())
            ->get()
            ->toArray();
    }

    protected function getSelectColumns()
    {
        return ['id', 'owner_type_id', 'name', 'code'];
    }

}
