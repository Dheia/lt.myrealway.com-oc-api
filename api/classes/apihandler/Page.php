<?php namespace ApiHandler;

use ApiResponse;
use ApiStorage;

class Page
{
    public function handle($params, ApiStorage $storage, ApiResponse $response)
    {
        if (!$page = $storage->getJson("page/$params"))
        {
            return false;
        }

        $owner = $storage->getJson("{$page->owner_type}/{$page->owner_id}");

        $response->add('page', $params, $page);

        $response->add($page->owner_type, $page->owner_id, $owner);

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
