<?php

namespace RedJasmine\User\Domain\Services\Login;


use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\Facades\UserLoginServiceProvider;

class UserLoginService
{


    public function captcha(Data\UserLoginData $data) : bool
    {
        $provider = UserLoginServiceProvider::create($data->provider);
        $provider->captcha($data);
        return true;
    }

    protected function attempt(Data\UserLoginData $data) : User
    {
        // 使用服务提供者的登陆方法 进行登陆
        $provider = UserLoginServiceProvider::create($data->provider);

        return $provider->login($data);

    }


    public function login(Data\UserLoginData $data) : UserTokenData
    {

        // 使用服务提供者的登陆方法 进行登陆
        $user = $this->attempt($data);
        // 返回 token
        return $this->token($user);

    }

    public function token(User $user) : UserTokenData
    {

        $token                  = Auth::guard('api')->login($user);
        $userToken              = new UserTokenData();
        $userToken->accessToken = (string) $token;
        $userToken->expire      = (int) (config('jwt.ttl') * 60);
        return $userToken;


    }

}
