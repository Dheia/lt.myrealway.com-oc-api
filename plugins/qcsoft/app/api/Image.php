<?php namespace Qcsoft\App\Api;

class Image
{
    public function index($entityType, $thumbSize, $entityIdsStr = null)
    {
        $entityMap = [
            'catalogitem' => [
                \Qcsoft\App\Models\Catalogitem::class,
                'sizes' => [
                    'md' => ['main_image', 120, 120, ['mode' => 'crop']],
                ],
            ],
        ];

        if (!$entityConfig = array_get($entityMap, $entityType))
        {
            return false;
        }

        if (!$thumbConfig = array_get($entityConfig['sizes'], $thumbSize))
        {
            return false;
        }

        $entityModel = $entityConfig[0];

        list($attributeName, $width, $height, $params) = $thumbConfig;

//        dump(compact('entityModel', 'thumbConfig', 'entityConfig', 'attributeName', 'width', 'height', 'params'));

        $query = (new $entityModel)->query();

        if ($entityIdsStr)
        {
            $entityIds = array_filter(explode(',', $entityIdsStr), function ($item)
            {
                return is_numeric($item) && $item > 0;
            });

            if (count($entityIds))
            {
                $query->whereIn('id', $entityIds);
            }
        }

        $query->select('id');

        $query->with($attributeName);

        $records = $query->get();

        $result = [];

        foreach ($records as $record)
        {
            $thumb = $record[$attributeName] ?
                $record[$attributeName]->getThumb($width, $height, $params) :
                null;

            $result[$record->id] = $thumb;
        }

//        dd($result);

        return $result;
    }

}
