<?php

namespace Qkor\Service;

use Qkor\Entity\User;

class UserService extends ServiceBase {
    public function addUser($username, $password) : false|string {
        $query = $this->db->prepare("INSERT INTO user (username, password, created, updated) VALUES (?, ?, ?, ?)");
        if($query->execute([$username, $password, time(), time()]))
            return (int)$this->db->lastInsertId();
        return false;
    }

    public function getUser($uid) : false|User {
        $query = $this->db->prepare("SELECT id, username FROM user WHERE id=?");
        if($query->execute([$uid])){
            $query->setFetchMode(\PDO::FETCH_CLASS, 'Qkor\Entity\User');
            return $query->fetch();
        }
        return false;
    }

    public function getUserByUsername($username) : false|User {
        $query = $this->db->prepare("SELECT id, username FROM user WHERE username=?");
        if($query->execute([$username])){
            $query->setFetchMode(\PDO::FETCH_CLASS, 'Qkor\Entity\User');
            return $query->fetch();
        }
        return false;
    }

    public function createUserSession($uid){
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $query = $this->db->prepare("INSERT INTO session (token, uid, expires, created, updated) VALUES (?, ?, ?, ?, ?)");
        if($query->execute([$token, $uid, time()+3600000, time(), time()]))
            return $token;
        return false;
    }
}
