<?php

namespace RedJasmine\Support\Services;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Enums\UserType;

class SystemUser implements UserInterface
{


    public function __construct(public int $uid = 0)
    {
    }


    public function getUserType() : string|int
    {
        return UserType::SYSTEM->value;
    }

    public function getUID() : string|int
    {
        return $this->uid;
    }

    public function getNickname() : ?string
    {
        return '';
    }

    public function getAvatar() : ?string
    {
        return null;
    }


}
