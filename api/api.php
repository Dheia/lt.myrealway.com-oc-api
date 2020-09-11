<?php
$time = microtime(true);
spl_autoload_register(function ($classname)
{
    $parts = explode('\\', $classname);

    $classFilename = array_pop($parts);

    $namespaceDirname = strtolower(implode('/', $parts));

    if (file_exists($filename = "api/classes/$namespaceDirname/$classFilename.php"))
    {
        require $filename;
    }
});

$storage = ApiStorage::getDefault();

$response = new ApiResponse($storage);

$path = $_SERVER['REQUEST_URI'];

$time = microtime(true);

if (
    preg_match('/^\/api\/([a-z]+)(.+)?/', $path, $matches) &&
    count($matches) >= 2 &&
    class_exists($classname = 'ApiHandler\\' . ucfirst($matches[1])) &&
    (new $classname)->handle(isset($matches[2]) ? $matches[2] : '', $storage, $response) !== false
)
{
    header('Access-Control-Allow-Origin: *');

    if (1)
    {
        echo 'Time: ' . (microtime(true) - $time) . '<hr/>';

        echo $response->json(1);
    }
    else
    {
        echo $response->json();
    }
//echo '<br/><br/><br/><br/><br/>'.(microtime(true) - $time);
}
else
{
    header('Not found', true, 404);
}
