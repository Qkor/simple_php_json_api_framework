<?php

use Qkor\Config\Config;

spl_autoload_register(function ($classname){
    $classname = str_replace('Qkor\\','',$classname);
    $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname).'.php';
    if (file_exists($path)) {
        require $path;
        return true;
    }
    return false;
});

header('Content-Type: application/json');

$url = parse_url($_SERVER['REQUEST_URI']);
$path = array_slice(explode('/',$url['path']),Config::config['urlPathOffset']);
$controllerName = ucfirst($path[0]);
$function = $path[1];
if(ctype_alnum($controllerName) && ctype_alnum($function)){
    $fullControllerName = "\\Qkor\\Controller\\" . ucfirst($path[0]) . "Controller";
    if(method_exists($fullControllerName, $function)){
        try {
            $controller = new $fullControllerName();
            $response = $controller->$function();
            echo json_encode($response);
        } catch (Throwable) {
            http_response_code(500);
            echo json_encode(['error' => 'internal server error']);
        }
        die();
    }
}
http_response_code(404);
echo json_encode(['error' => 'wrong route']);

