<?php

namespace Qkor\Entity;

class Session extends EntityBase{
    protected int $uid;
    protected int $expires;
    protected int $created;
    protected int $updated;
    protected int $is_deleted;

    public function getUid() : int{
        return $this->uid;
    }
}
