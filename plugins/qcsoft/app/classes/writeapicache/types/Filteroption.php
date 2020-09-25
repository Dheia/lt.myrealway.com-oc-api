<?php namespace Qcsoft\App\Classes\Writeapicache\Types;

use Qcsoft\App\Classes\Writeapicache\Storagetypes\AllInOneHandler;

class Filteroption extends AllInOneHandler
{
    public function getById($id)
    {
        return \Qcsoft\App\Models\Filteroption
            ::select($this->getSelectColumns())
            ->where('id', $id)
            ->first()
            ->toArray();
    }

    public function getAll()
    {
        return \Qcsoft\App\Models\Filteroption
            ::select($this->getSelectColumns())
            ->get()
            ->toArray();
    }

    protected function getSelectColumns()
    {
        return ['id', 'filter_id', 'name', 'slug', 'sort_order'];
    }

}
