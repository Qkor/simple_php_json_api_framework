<?php

namespace MF\Controller;
use MF\Config\Config;

abstract class ControllerBase{
    protected \PDO $db;
    protected array|null $input;
    protected array|null $params;
    public function __construct(){
        try {
            $this->db = new \PDO("mysql:host=".Config::config['host'].";dbname=".Config::config['dbName'], Config::config['dbUser'], Config::config['dbPass']);
        } catch (\Exception $_) {
            $response = $this->errorResponse(500, 'Internal server error');
            echo json_encode($response);
            die();
        }
        $this->input = json_decode(file_get_contents('php://input'), true);
        $this->params = $_GET;
    }
    protected function errorResponse(int $httpCode, string $errorMessage): array{
        http_response_code($httpCode);
        return ['error' => $errorMessage];
    }
}