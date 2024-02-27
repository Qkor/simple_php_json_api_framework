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
if(count($path)>=2 && ctype_alnum($path[0]) && ctype_alnum($path[1])){
    $controllerName = "\\Qkor\\Controller\\" . ucfirst($path[0]) . "Controller";
    if(class_exists($controllerName)){
        try{
            $controller = new $controllerName();
            $method = $controller->getRoute($path[1]);
            if(!$method && Config::config['autoRoutes'])
                $method = $path[1];
            if(method_exists($controller, $method)){
                $response = $controller->$method();
                echo json_encode($response);
                die();
            }
        } catch (Throwable $e){
            if($e->getCode() == 1){
                echo json_encode(ErrorHandler::getErrorResponse(1, $e->getMessage()));
            } else {
                if(Config::config['debug'])
                    echo $e->getMessage();
                echo json_encode(ErrorHandler::getServerErrorResponse());
            }
            die();
        }
    }
}
echo json_encode(ErrorHandler::getRouteErrorResponse());
