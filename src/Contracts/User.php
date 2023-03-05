<?php

namespace RedJasmine\Support\Contracts;

/**
 * 用户协议
 */
interface User
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
     * 所属者
     * @return static|null
     */
    public function getOwner() : static|null;
}
