<?php

use Qkor\Config\Config;
use Qkor\Error\ErrorHandler;

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
if(count($path)>=2){
    $controllerName = ucfirst($path[0]);
    $function = $path[1];
    if(ctype_alnum($controllerName) && ctype_alnum($function)){
        $fullControllerName = "\\Qkor\\Controller\\" . ucfirst($path[0]) . "Controller";
        if(method_exists($fullControllerName, $function)){
            try {
                $controller = new $fullControllerName();
                $response = $controller->$function();
                echo json_encode($response);
            } catch (Throwable $e) {
                if($e->getCode() == 1){
                    echo json_encode(ErrorHandler::getErrorResponse(1, $e->getMessage()));
                } else {
                    if(Config::config['debug'])
                        echo $e->getMessage();
                    echo json_encode(ErrorHandler::getServerErrorResponse());
                }
            }
            die();
        }
    }
}
echo json_encode(ErrorHandler::getRouteErrorResponse());
