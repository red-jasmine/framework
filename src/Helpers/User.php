<?php

namespace RedJasmine\Support\Helpers;

use RedJasmine\Support\Contracts\UserInterface;

class User implements UserInterface
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

    public function getNickname() : ?string
    {
        return  '';
    }

    public function getAvatar() : ?string
    {
        return  '';
    }


}
