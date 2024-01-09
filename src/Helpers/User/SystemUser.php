<?php

namespace RedJasmine\Support\Helpers\User;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Enums\UserType;

class SystemUser implements UserInterface
{


    public function __construct(public int $id = 0, protected string $nickname = 'system')
    {
    }


    public function getType() : string
    {
        return UserType::SYSTEM->value;
    }

    public function getID() : int
    {
        return $this->id;
    }

    public function getNickname() : ?string
    {
        return $this->nickname;
    }

    public function getAvatar() : ?string
    {
        return null;
    }


}
