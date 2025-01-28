<?php

namespace RedJasmine\User\Domain\Services\Login;


use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\Facades\UserLoginServiceProvider;

class UserLoginService
{


    public function login(Data\UserLoginData $data) : UserTokenData
    {
        // 使用服务提供者的登陆方法 进行登陆
        $user = UserLoginServiceProvider::create($data->provider)
                                        ->login($data);

        // 返回 token
        return $this->token($user);

    }

    public function token(User $user) : UserTokenData
    {

        // 动态 guard TODO
        $token                   = Auth::guard('api')->login($user);
        $userToken               = new UserTokenData();
        $userToken->access_token = $token;
        $userToken->token_type   = 'bearer';
        $userToken->expire       = config('jwt.ttl') * 60;
        return $userToken;


    }

}
