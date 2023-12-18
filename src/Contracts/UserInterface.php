<?php

namespace RedJasmine\Support\Contracts;

/**
 * 用户接口
 */
interface UserInterface
{


    /**
     * 类型
     * @return string
     */
    public function getType() : string;


    /**
     * ID
     * @return int
     */
    public function getID() : int;


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
