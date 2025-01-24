<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class SocialiteLoginServiceProvider implements UserLoginServiceProviderInterface
{

    public function __construct()
    {
    }

    public const NAME = 'socialite';

    public function login(UserLoginData $data) : User
    {
        // 验证参数

        // 根据Code 转换用户信息

        // 根据用户信息 查询 社交账号的 绑定的信息

        // 根据社交账号绑定的信息 查询用户信息

        // 返回用户信息
    }


}
