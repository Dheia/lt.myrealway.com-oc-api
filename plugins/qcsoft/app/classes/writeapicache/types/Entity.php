<?php namespace Qcsoft\App\Classes\Writeapicache\Types;

use Qcsoft\App\Classes\Writeapicache\TypeHandlerAll;

class Entity extends TypeHandlerAll
{
    public function getById($id)
    {
        return \Qcsoft\App\Models\Entity
            ::select($this->getSelectColumns())
            ->where([
                'is_exposed' => true,
                'id'         => $id,
            ])
            ->first()
            ->toArray();
    }

    public function getAll()
    {
        return \Qcsoft\App\Models\Entity
            ::select($this->getSelectColumns())
            ->where('is_exposed', true)
            ->get()
            ->toArray();
    }

    protected function getSelectColumns()
    {
        return ['id', 'name'];
    }

}
