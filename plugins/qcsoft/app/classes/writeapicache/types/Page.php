<?php namespace Qcsoft\App\Classes\Writeapicache\Types;

use Qcsoft\App\Models\Entity;

class Page
{
    public function getById($id)
    {

    }

    public function getByIdList($idList)
    {

    }

    public function getRange($offset, $limit)
    {
        $items = \Qcsoft\App\Models\Page::orderBy('id')->skip($offset)->take($limit)->get();

        $groups = $items->groupBy('owner_type_id');

        $resultList = [];

        foreach ($groups as $type_id => $items)
        {
            $modelclass = Entity::classnameById($type_id);

            $require = $modelclass::getPageRequireEntities($items->pluck('owner_id'));

            foreach ($items as $item)
            {
                $result = $item->toArray();

                $result['require'] = array_get($require, $item->id, []);

                $resultList[$item->id] = $result;
            }
        }

        return $resultList;
    }

}
