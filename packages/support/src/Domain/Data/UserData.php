<?php

namespace RedJasmine\Support\Domain\Data;

use Illuminate\Contracts\Support\Arrayable;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Stringable;


class UserData extends Data implements UserInterface, Arrayable, Stringable
{

    public function __toString() : string
    {
        return $this->toJson();
    }

    /**
     * @param  string  $type
     * @param  int|string  $id
     * @param  string|null  $nickname
     * @param  string|null  $avatar
     */
    public function __construct(
        public string $type,
        public int|string $id,
        public ?string $nickname = null,
        public ?string $avatar = null,
    ) {
    }

    public static function fromUserInterface(UserInterface $user) : static
    {
        return (new static(
            type: $user->getType(),
            id: $user->getID(),
            nickname: $user->getNickname(),
            avatar: $user->getAvatar()
        ));
    }


    /**
     * 用户类型
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }


    /**
     * 获取用户ID
     * @return string
     */
    public function getID() : string
    {
        return $this->id;
    }


    /**
     * 获取昵称
     * @return string|null
     */
    public function getNickname() : ?string
    {
        return $this->nickname;
    }


    /**
     * 获取头像
     * @return string|null
     */
    public function getAvatar() : ?string
    {
        return $this->avatar;
    }

    public function getUserData() : array
    {
        return $this->toArray();
    }


}
