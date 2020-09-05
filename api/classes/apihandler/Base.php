<?php namespace ApiHandler;

use ApiResponse;
use ApiStorage;

class Base
{
    public function handle($params, ApiStorage $storage, ApiResponse $response)
    {
        if ($params !== 'all')
        {
            return false;
        }

        $response->set($storage->get('base.json'));
    }

}
