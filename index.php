<?php

spl_autoload_register(function ($classname){
    $classname = str_replace('MF\\','',$classname);
    $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname).'.php';
    if (file_exists($path)) {
        require $path;
        return true;
    }
    return false;
});

header('Content-Type: application/json');

$url = parse_url($_SERVER['REQUEST_URI']);
$path = array_slice(explode('/',$url['path']),3);
$controllerName = ucfirst($path[0]);
$function = $path[1];

try {
    if(!ctype_alnum($controllerName) || !ctype_alnum($function))
        throw new Exception();
    $fullControllerName = "\\MF\\Controller\\" . ucfirst($path[0]) . "Controller";
    $controller = new $fullControllerName();
    $response = $controller->$function();
    echo json_encode($response);
} catch (Throwable){
    http_response_code(404);
    echo json_encode(['error' => 'wrong route']);
}
