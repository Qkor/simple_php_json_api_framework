<?php

namespace MF\Controller;
use MF\Config\Config;

abstract class ControllerBase{
    protected \PDO $db;
    public function __construct(){
        try {
            $this->db = new \PDO("mysql:host=".Config::config['host'].";dbname=".Config::config['dbName'], Config::config['dbUser'], Config::config['dbPass']);
        } catch (\Exception $_) {
            http_response_code(500);
            echo json_encode(['error' => 'internal server error']);
            die();
        }
    }
}