<?php

namespace RedJasmine\User\Domain\Services\Login;


use RedJasmine\User\Domain\Services\Login\Facades\UserLoginServiceProvider;

class UserLoginService
{


    public function login(Data\UserLoginData $data)
    {
        // 使用服务提供者的登陆方法 进行登陆
        $user = UserLoginServiceProvider::create($data->provider)->login($data->toArray());

        $token = Auth::login($user);
        // 生成 token  和  刷新 token
        // token  生成 返回，用户id、昵称、类型、

    }

}
