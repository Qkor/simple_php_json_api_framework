<?php

namespace Qkor\Controller;
use Exception;
use PDO;
use Qkor\Config\Config;
use Qkor\Entity\Session;
use Qkor\Entity\User;
use Qkor\Error\ErrorHandler;
use Qkor\Service\UserService;

abstract class ControllerBase{

    /**
     * @var array
     * Controller's routing, for mapping routes to methods. Formatted: ['route_name' => 'method_name']
     */
    protected array $routes = [];

    /**
     * @var PDO
     * Database connection
     */
    protected PDO $db;

    /**
     * @var $input array
     * Associative array from request's json. Filled when $this->validateInput() is called.
     */
    protected array $input = [];

    /**
     * @var array
     * Request's query params array. Filled when $this->validateInput() is called.
     */
    protected array $params = [];

    protected UserService $userService;

    /**
     * @var User|null
     * Logged user entity based on session token or null when session token is invalid or not provided
     */
    protected User|null $user = null;

    /**
     * @var Session|null
     * Session entity based on session token or null when session token is invalid or not provided
     */
    protected Session|null $session = null;

    public function __construct(){
        $this->db = new PDO("mysql:host=".Config::config['host'].";dbname=".Config::config['dbName'], Config::config['dbUser'], Config::config['dbPass']);
        if(!Config::config['debug'])
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->userService = new UserService($this->db);
        if(isset($_SERVER['HTTP_TOKEN']) && $session = $this->userService->checkToken($_SERVER['HTTP_TOKEN'])){
            if($user = $this->userService->getUser($session->getUid())){
                $this->session = $session;
                $this->user = $user;
            }
        }
    }

    /**
     * Returns controller's method for given route name based on $routes array
     * @param string $name
     * @return false|string
     */
    public function getRoute(string $name) : false|string {
        return $this->routes[$name] ?? false;
    }

    /**
     * Returns API error response
     * @param int $errorId id of the error in Qkor\Error\ErrorHandler.php
     * @param string|null $errorMessage optional custom message
     * @return array
     */
    protected function errorResponse(int $errorId = 0, string $errorMessage = null): array{
        return ErrorHandler::getErrorResponse($errorId, $errorMessage);
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
     *  Append '?' to validator name if the parameter is not required.
     *  Sets validated parameters from json in $this->input and from query params in $this->params
     *  Not required parameters are set to null if not provided.
     *  On validation failure throws exception to be caught in index.php
     * @param array $jsonParams
     * @param array $queryParams
     * @return void
     * @throws Exception
     */
    protected function validateInput(array $jsonParams = [], array $queryParams = []) : void{
        $input = json_decode(file_get_contents('php://input'), true);
        $params = $_GET;
        foreach($jsonParams as $param => $type){
            if($type[-1] == '?'){
                if(!isset($input[$param])){
                    $this->input[$param] = null;
                    continue;
                }
                $type = substr($type,0,-1);
            }
            if(!isset($input[$param]) || !$this->validateValue($input[$param],$type)){
                $exceptionMessage = $param . ' value invalid';
                throw new Exception($exceptionMessage, 1);
            }
            $this->input[$param] = $input[$param];
        }
        foreach($queryParams as $param => $type){
            if($type[-1] == '?'){
                if(!isset($params[$param])){
                    $this->params[$param] = null;
                    continue;
                }
                $type = substr($type,0,-1);
            }
            if(!isset($params[$param]) || !$this->validateValue($params[$param],$type)){
                $exceptionMessage = $param . ' value invalid';
                throw new Exception($exceptionMessage, 1);
            }
            $this->params[$param] = $params[$param];
        }
    }
}
