<?php

namespace RedJasmine\Support\Contracts;

interface UserInfo
{

    /**
     * 获取昵称
     * @return string|null
     */
    public function getNickname() : ?string;


    /**
     * 获取头像
     * @return string|null
     */
    public function getAvatar() : ?string;

}
