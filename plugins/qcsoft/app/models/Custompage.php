<?php namespace Qcsoft\App\Models;

use Qcsoft\App\Modelsbase\CustompageBase;

class Custompage extends CustompageBase
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];

    public static function getPageRequireEntities($ids)
    {
//        $gpages=static::query()
//            ->whereIn('id', $ids)
//            ->with('')

        return [];
    }

    public function getPageApiData()
    {
        $data = [
            'home' => [
                'bestsellerProducts' => [1022, 1023, 1024, 1025, 1026, 1027, 1028],
                'bestsellerBundles'  => [6, 7, 8, 10, 11, 12],
            ]
        ];

        $result = array_get($data, $this->code);

        $result['custompage_code'] = $this->code;

        return $result;
    }

    public function getPageApiDataHome()
    {

    }
}
