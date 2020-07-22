<?php namespace Qcsoft\Bvb\Controllers;

use Backend\Classes\Controller;
use Qcsoft\App\GraphQL\AppContext;
use Qcsoft\App\GraphQL\AppGraphQL;

class Index extends Controller
{
    public function index()
    {
        $this->layout = plugins_path('qcsoft/bvb/layout/default');
    }

    public function onGetListData()
    {
        $appContext = new AppContext();

        $result = AppGraphQL::execute(\Request::input('query'), [], $appContext);

        return $result;
    }

}
