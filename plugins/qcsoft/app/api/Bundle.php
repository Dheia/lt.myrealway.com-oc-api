<?php namespace Qcsoft\App\Api;

use Qcsoft\App\Models\Bundle as BundleModel;

class Bundle
{
    public function base()
    {
        $result = BundleModel::select([
            'id',
            'name',
            'owner_type',
            'owner_id',
            'default_price',
        ])->get();

        return $result;
    }

}
