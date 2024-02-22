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
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
        } catch (\Exception) {
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

    protected function validateValue($value, $type) : bool{
        return match ($type) {
            'string' => is_string($value) && strlen($value),
            'int' => is_int($value),
            'numeric' => is_numeric($value),
            'array' => is_array($value),
            default => false,
        };
    }

    protected function validateInput(array $jsonParams = [], array $queryParams = []) : void{
        foreach($jsonParams as $param => $type){
            if(!isset($this->input[$param]) || !$this->validateValue($this->input[$param],$type)){
                $response = $this->errorResponse(400, $param . ' value invalid');
                echo json_encode($response);
                die();
            }
        }
        foreach($queryParams as $param => $type){
            if(!isset($this->params[$param]) || !$this->validateValue($this->params[$param],$type)){
                $response = $this->errorResponse(400, $param . ' value invalid');
                echo json_encode($response);
                die();
            }
        }
    }
}