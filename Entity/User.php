<?php

namespace Qkor\Entity;

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