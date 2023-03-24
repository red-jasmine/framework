<?php

namespace RedJasmine\Support\Contracts;

/**
 * 用户协议
 */
interface UserInterface
{
    /**
     * 用户类型
     * @return string|int
     */
    public function getUserType() : string|int;

    /**
     * 获取用户ID
     * @return int|string
     */
    public function getUID() : string|int;


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
