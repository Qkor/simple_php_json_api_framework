<?php

require_once __DIR__ . '/Controller/Controller.php';

header('Content-Type: application/json');

$url = parse_url($_SERVER['REQUEST_URI']);
$path = array_slice(explode('/',$url['path']),3);
$controllerName = "\\MF\\Controller\\" . ucfirst($path[0]);
$function = $path[1];

try {
    $controller = new $controllerName();
    $response = $controller->$function();
    if(is_array($response)) echo json_encode($response);
    else{
        http_response_code(500);
        echo json_encode(['error' => 'internal server error']);
    }
} catch (Throwable $_){
    http_response_code(404);
    echo json_encode(['error' => 'wrong route']);
}
