<?php

namespace RedJasmine\Support\Helpers;

use RedJasmine\Support\Contracts\UserInterface;

class UserObjectBuilder implements UserInterface
{
    /**
     * @param array{type:string,uid:string,nickname:string|null,avatar:string|null} $data
     */
    public function __construct(array $data)
    {
        $this->userType = $data['type'] ?? '';
        $this->uid      = $data['uid'] ?? '';
        $this->avatar   = $data['avatar'] ?? '';
        $this->nickname = $data['nickname'] ?? '';
    }

    /**
     * @param int|string $userType
     * @return UserObjectBuilder
     */
    public function setUserType(int|string $userType) : UserObjectBuilder
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * @param int|string $uid
     * @return UserObjectBuilder
     */
    public function setUid(int|string $uid) : UserObjectBuilder
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @param string|null $nickname
     * @return UserObjectBuilder
     */
    public function setNickname(?string $nickname) : UserObjectBuilder
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @param string|null $avatar
     * @return UserObjectBuilder
     */
    public function setAvatar(?string $avatar) : UserObjectBuilder
    {
        $this->avatar = $avatar;
        return $this;
    }




    protected string|int $userType;

    /**
     * 用户类型
     * @return string
     */
    public function getUserType() : string
    {
        return $this->userType;
    }

    protected string|int $uid;

    /**
     * 获取用户ID
     * @return int
     */
    public function getUID() : int
    {
        return $this->uid;
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
