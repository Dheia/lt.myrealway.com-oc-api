<?php

class ApiHandler
{
    public function handle($path, ApiStorage $storage, ApiResponse $response)
    {
        $regexp = "/^\/api\/([^\/]+)\/([a-z0-9-\/]+)/";

        preg_match($regexp, $path, $matches);

        if (count($matches) !== 3)
        {
            return false;
        }

        array_shift($matches);

        list($rootHandlerName, $params) = $matches;

        $classname = 'ApiHandler\\' . ucfirst($rootHandlerName);

        if (!class_exists($classname))
        {
            return false;
        }

        $rootHandler = new $classname;

        if ($rootHandler->handle($params, $storage, $response) === false)
        {
            return false;
        }

        return true;
    }

}
