<?php

namespace Qkor\Controller;
use Exception;
use Qkor\Config\Config;

abstract class ControllerBase{
    /**
     * @var \PDO
     * Database connection
     */
    protected \PDO $db;

    /**
     * @var $input array|null
     * Associative array from request's json
     */
    protected array|null $input;

    /**
     * @var array|null
     * Request's query params array
     */
    protected array|null $params;
    public function __construct(){
        $this->db = new \PDO("mysql:host=".Config::config['host'].";dbname=".Config::config['dbName'], Config::config['dbUser'], Config::config['dbPass']);
        if(!Config::config['debug'])
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
        $this->input = json_decode(file_get_contents('php://input'), true);
        $this->params = $_GET;
    }

    /**
     * Returns API error response as an associative array
     * @param int $httpCode
     * @param string $errorMessage
     * @return string[]
     */
    protected function errorResponse(int $httpCode, string $errorMessage): array{
        http_response_code($httpCode);
        return ['error' => $errorMessage];
    }

    /**
     * Returns true if value passes validation of given type and false otherwise.
     * New validator types can be easily added to this function
     * @param $value
     * @param $type
     * @return bool
     */
    protected function validateValue($value, $type) : bool{
        return match ($type) {
            'string' => is_string($value) && strlen($value),
            'int' => is_int($value),
            'numeric' => is_numeric($value),
            'array' => is_array($value),
            default => false,
        };
    }

    /**
     *  Validates parameters from request's json and query parameters based on associative arrays,
     *  structured: ['parameter_name'=>'validator'], where 'validator' is the name of the validator from validateValue method.
     *  On validation failure throws exception to be caught in index.php
     * @param array $jsonParams
     * @param array $queryParams
     * @return void
     * @throws Exception
     */
    protected function validateInput(array $jsonParams = [], array $queryParams = []) : void{
        foreach($jsonParams as $param => $type){
            if(!isset($this->input[$param]) || !$this->validateValue($this->input[$param],$type)){
                $exceptionMessage = $param . ' value invalid';
                throw new Exception($exceptionMessage, 1);
            }
        }
        foreach($queryParams as $param => $type){
            if(!isset($this->params[$param]) || !$this->validateValue($this->params[$param],$type)){
                $exceptionMessage = $param . ' value invalid';
                throw new Exception($exceptionMessage, 1);
            }
        }
    }
}
