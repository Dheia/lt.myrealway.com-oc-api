<?php namespace ApiHandler;

use ApiResponse;
use ApiStorage;

class Records
{
    public function handle($params, ApiStorage $storage, ApiResponse $response)
    {
        print_r($params);
//        die;
    }

}
