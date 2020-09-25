<?php namespace Qcsoft\App\Classes\Writeapicache\Types;

use Qcsoft\App\Classes\Writeapicache\Storagetypes\AllInOneHandler;

class Filter extends AllInOneHandler
{
    public function getById($id)
    {
        return \Qcsoft\App\Models\Filter
            ::select($this->getSelectColumns())
            ->where('id', $id)
            ->first()
            ->toArray();
    }

    public function getAll()
    {
        return \Qcsoft\App\Models\Filter
            ::select($this->getSelectColumns())
            ->get()
            ->toArray();
    }

    protected function getSelectColumns()
    {
        return ['id', 'name', 'slug', 'sort_order'];
    }

}
