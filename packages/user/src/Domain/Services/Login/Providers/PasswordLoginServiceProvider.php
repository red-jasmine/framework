<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class PasswordLoginServiceProvider implements UserLoginServiceProviderInterface
{
    public const string NAME = 'password';


    public function login(UserLoginData $data) : User
    {
        // 按用户名称查询 用户
        $user = app(UserReadRepositoryInterface::class)->findByName($data->data['account']);

        // 验证密码
        $user->checkPassword($data->data['password']);

        // 返回用户信息

        return $user;
    }


}
