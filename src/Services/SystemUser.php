<?php

namespace RedJasmine\Support\Services;

use Liushoukun\LaravelProjectTools\Contracts\User;
use RedJasmine\Support\Enums\UserType;

class SystemUser implements User
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

    public function getOwner() : User|null
    {
        return null;
    }


}
