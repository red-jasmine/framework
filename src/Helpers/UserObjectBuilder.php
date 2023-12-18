<?php

namespace RedJasmine\Support\Helpers;

use RedJasmine\Support\Contracts\UserInterface;

class UserObjectBuilder implements UserInterface
{
    /**
     * @param array{type:string,id:string,nickname:string|null,avatar:string|null} $data
     */
    public function __construct(array $data)
    {
        $this->type     = $data['type'] ?? '';
        $this->id       = $data['id'] ?? '';
        $this->avatar   = $data['avatar'] ?? '';
        $this->nickname = $data['nickname'] ?? '';
    }


    protected string|int $type;

    /**
     * 用户类型
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    protected string|int $id;

    /**
     * 获取用户ID
     * @return int
     */
    public function getID() : int
    {
        return $this->id;
    }


    protected string|null $nickname;

    /**
     * 获取昵称
     * @return string|null
     */
    public function getNickname() : ?string
    {
        return $this->nickname;
    }

    protected string|null $avatar;

    /**
     * 获取头像
     * @return string|null
     */
    public function getAvatar() : ?string
    {
        return $this->avatar;
    }


}
