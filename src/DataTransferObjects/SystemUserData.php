<?php

namespace RedJasmine\Support\DataTransferObjects;

use RedJasmine\Support\Contracts\UserInterface;
use Spatie\LaravelData\Data;

class SystemUserData extends Data implements UserInterface
{

    /**
     * @param string      $type
     * @param int         $id
     * @param string|null $nickname
     * @param string|null $avatar
     */
    public function __construct(
        public string  $type = 'system',
        public int     $id = 0,
        public ?string $nickname = '系统',
        public ?string $avatar = null,
    )
    {
    }

    public static function fromUserInterface(UserInterface $user) : static
    {
        return new static(
            type:     $user->getType(),
            id:       $user->getID(),
            nickname: $user->getNickname(),
            avatar:   $user->getAvatar()
        );
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
     * @return int
     */
    public function getID() : int
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


}
