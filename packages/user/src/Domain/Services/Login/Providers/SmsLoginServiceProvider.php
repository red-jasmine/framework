<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class SmsLoginServiceProvider implements UserLoginServiceProviderInterface
{


    public const string NAME = 'sms';

    public function login(UserLoginData $data) : User
    {
        // 获取验证码
        $code = $data->data['code'];
        // 获取账户信息
        $account = $data->data['account'];

        // 验证验证码 TODO 调度 验证码服务

        // 查询用户信息
        $user = app(UserReadRepositoryInterface::class)->findByAccount($account);


        // 返回用户信息

        return $user;


    }


}
