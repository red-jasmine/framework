<?php

namespace RedJasmine\Support\Data;

use Illuminate\Contracts\Support\Arrayable;
use RedJasmine\Support\Contracts\UserInterface;
use Stringable;

class System extends UserData implements UserInterface, Arrayable, Stringable
{

    public function __toString() : string
    {
        return $this->toJson();
    }


    const string TYPE = 'system';

    public function __construct(
        public string|int $id = self::TYPE,
        public string $type = 'system',
        public ?string $nickname = '系统',
        public ?string $avatar = null,
    ) {
    }

    public static function make(...$args) : static
    {
        return new static(...$args);
    }


    public static function isSystem(UserInterface $user) : bool
    {
        return ($user->getType() === static::TYPE);
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