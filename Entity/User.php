<?php

namespace MF\Entity;

class User extends EntityBase{
    protected int $id;
    protected string $username;

    public function id(): int {
        return $this->id;
    }
    public function getUsername() : string {
        return $this->username;
    }
}