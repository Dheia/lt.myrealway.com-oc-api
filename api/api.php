<?php
$time = microtime(true);
spl_autoload_register(function ($classname)
{
    if (file_exists($filename = "api/classes/$classname.php"))
    {
        require $filename;
    }
});

$storage = new ApiStorage(realpath(__DIR__ . '/../storage/apicache'));

$response = new ApiResponse();

$handler = new ApiHandler();

$path = $_SERVER['REQUEST_URI'];

if ($handler->handle($path, $storage, $response) === false)
{
    header('Not found', true, 404);

    return;
}

header('Access-Control-Allow-Origin: *');

//echo '<pre>';
echo $response->json();
//echo '</pre>';
//echo '<br/><br/><br/><br/><br/>'.(microtime(true) - $time);
