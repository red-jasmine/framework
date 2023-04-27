<?php

namespace RedJasmine\Support\Helpers;

use RedJasmine\Support\Contracts\UserInterface;

class UserObjectBuilder implements UserInterface
{
    /**
     * @param array{type:string,uid:string,nickname:string|null,avatar:string|null} $data
     */
    public function __construct(public array $data)
    {
    }

    /**
     * 用户类型
     * @return string|int
     */
    public function getUserType() : string|int
    {
        return $this->data['type'];
    }

    /**
     * 获取用户ID
     * @return int|string
     */
    public function getUID() : string|int
    {
        return $this->data['uid'];
    }

    /**
     * 获取昵称
     * @return string|null
     */
    public function getNickname() : ?string
    {
        return (string)($this->data['nickname'] ?? null);
    }

    /**
     * 获取头像
     * @return string|null
     */
    public function getAvatar() : ?string
    {
        return (string)($this->data['avatar'] ?? null);
    }


}
