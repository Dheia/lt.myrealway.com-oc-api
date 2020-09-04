<?php namespace Qcsoft\App\Api;

use Qcsoft\App\Models\Catalogitem as CatalogitemModel;

class Catalogitem
{
    public function base()
    {
        $result = CatalogitemModel::select([
            'id',
            'name',
            'item_type',
            'item_id',
            'default_price',
        ])->get();

        return $result;
    }

}
