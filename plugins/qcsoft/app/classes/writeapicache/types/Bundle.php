<?php namespace Qcsoft\App\Classes\Writeapicache\Types;

use Qcsoft\App\Classes\Writeapicache\Storagetypes\OneByOneHandler;

class Bundle extends OneByOneHandler
{
    public function getRange()
    {
        $resultList = $this->getBaseList();

        $layout = \Qcsoft\App\Models\Layout::cached()->firstWhere('code', 'bundle_page_default');

        $pages = \Qcsoft\App\Models\Page
            ::where('layout_id', $layout->id)
            ->whereIn('owner_id', $resultList->pluck('id'))
            ->get()
            ->keyBy('owner_id');

        $resultList = $resultList->map(function ($item) use ($pages)
        {
            $result = $item->toArray();

            $result['catalogitem_id'] = $result['catalogitem']['id'];
            unset($result['catalogitem']);

            $result['page_id'] = $pages[$result['id']]->id;

            $result['bundle_product'] = $item->bundle_products->pluck('id');
            unset($result['bundle_products']);

            return $result;
        })
            ->keyBy('id')
            ->toArray();

        return $resultList;
    }

    public function getTotalCount()
    {
        return \Qcsoft\App\Models\Bundle::count();
    }

    public function getBaseQuery()
    {
        return \Qcsoft\App\Models\Bundle
            ::with([
                'catalogitem'     => function ($query)
                {
                    $query->select(['id', 'owner_type_id', 'owner_id']);
                },
                'bundle_products' => function ($query)
                {
                    $query->select(['id', 'bundle_id']);
                },
            ]);
    }

}
