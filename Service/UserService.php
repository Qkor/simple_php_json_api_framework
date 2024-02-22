<?php

namespace MF\Service;

use MF\Entity\User;

class UserService extends ServiceBase {
    public function addUser($username, $password) : false|string {
        $query = $this->db->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
        if($query->execute([$username,$password]))
            return $this->db->lastInsertId();
        return false;
    }
    public function getUser($id) : false|User {
        $query = $this->db->prepare("SELECT id, username FROM user WHERE id=?");
        if($query->execute([$id])){
            $query->setFetchMode(\PDO::FETCH_CLASS, 'MF\Entity\User');
            return $query->fetch();
        }
        return false;
    }
    public function getUserByUsername($username) : false|User {
        $query = $this->db->prepare("SELECT id, username FROM user WHERE username=?");
        if($query->execute([$username])){
            $query->setFetchMode(\PDO::FETCH_CLASS, 'MF\Entity\User');
            return $query->fetch();
        }
        return false;
    }
}