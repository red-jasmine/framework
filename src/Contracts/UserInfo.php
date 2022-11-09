<?php

namespace RedJasmine\Support\Contracts;

/**
 * 用户信息
 */
interface UserInfo extends User
{
    /**
     * 昵称
     * @return string|null
     */
    public function nickname() : ?string;


    /**
     * 用户头像
     * @return string|null
     */
    public function avatar() : ?string;
}
