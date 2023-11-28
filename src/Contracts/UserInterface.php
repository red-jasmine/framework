<?php

namespace RedJasmine\Support\Contracts;

/**
 * 用户接口
 */
interface UserInterface
{
    /**
     * 用户类型
     * @return string
     */
    public function getUserType() : string;

    /**
     * 获取用户ID
     * @return int
     */
    public function getUID() : int;


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
