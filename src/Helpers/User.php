<?php

namespace RedJasmine\Support\Helpers;

class User implements \RedJasmine\Support\Contracts\User
{
    public function __construct(public string $userType, public int $uid)
    {
    }


    public function getUserType() : string|int
    {
        return $this->userType;
    }

    public function getUID() : string|int
    {
        return $this->uid;
    }

    public function getOwner() : static|null
    {
        return null;
    }


}
