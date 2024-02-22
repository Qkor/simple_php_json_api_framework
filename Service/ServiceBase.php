<?php

namespace MF\Service;

abstract class ServiceBase{
    protected \PDO $db;
    public function __construct($db){
        $this->db = $db;
    }
}