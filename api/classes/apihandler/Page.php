<?php namespace ApiHandler;

use ApiResponse;
use ApiStorage;

class Page
{
    public function handle($params, ApiStorage $storage, ApiResponse $response)
    {
        if (!$page = $response->addObject('page', $params))
        {
            return false;
        }

        $owner = $response->addObject($page->owner_type, $page->owner_id);

        $classname = 'ApiHandler\\Page\\' . ucfirst($page->owner_type);

        if (!class_exists($classname))
        {
            return false;
        }

        $handler = new $classname;

        $handler->handle($page, $owner, $storage, $response);
    }

    protected function catalog($response, $storage)
    {

    }

    protected function pageProduct($page, $owner, ApiStorage $storage, ApiResponse $response)
    {

    }

    protected function pageBundle($page, $owner, ApiStorage $storage, ApiResponse $response)
    {

    }

}
