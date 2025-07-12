<?php

namespace RedJasmine\Support\Data;

use RedJasmine\Support\Contracts\UserInterface;

class System extends Data implements UserInterface
{

    public function __construct(
        public string $id = 'system',
        public string $type = 'system',
        public ?string $nickname = '系统',
        public ?string $avatar = null,
    ) {
    }

    public static function make(...$args) : static
    {
        return new static(...$args);
    }


    public function getType() : string
    {
        return $this->type;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getNickname() : ?string
    {
        return $this->nickname;
    }

    public function getAvatar() : ?string
    {
        return $this->avatar;
    }

    public function getUserData() : array
    {
        return $this->toArray();
    }


}