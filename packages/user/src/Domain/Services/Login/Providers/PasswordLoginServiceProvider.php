<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Domain\Exceptions\LoginException;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class PasswordLoginServiceProvider implements UserLoginServiceProviderInterface
{
    public const string NAME = 'password';


    /**
     * @param  UserLoginData  $data
     *
     * @return User
     * @throws LoginException
     */
    public function login(UserLoginData $data) : User
    {
        // 按用户名称查询 用户
        if ($user = app(UserReadRepositoryInterface::class)->findByAccount($data->data['account'])) {
            if (!Auth::validate(['id' => $user->id, 'password' => $data->data['password']])) {
                throw new LoginException('账号或者密码错误');
            }
            // 返回用户信息
            return $user;
        }

        throw new LoginException('账号或者密码错误');

    }


}
