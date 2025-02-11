<?php

namespace RedJasmine\User\Domain\Services\Register;

use Illuminate\Support\Str;
use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;
use RedJasmine\User\Domain\Services\Register\Facades\UserRegisterServiceProvider;

/**
 * 注册服务
 */
class UserRegisterService
{
    public function register(UserRegisterData $data) : User
    {
        $provider = UserRegisterServiceProvider::create($data->provider);

        $userData = $provider->register($data);

       return $this->makeUser($userData);
    }


    public function makeUser(UserData $data) : User
    {
        $user = User::make();

        // 用户注册功能呢

        // 验证是否允许注册

        $user->type         = $data->type;
        $user->username     = $data->username ?? $this->buildUserName();
        $user->nickname     = $data->nickname ?? $this->buildNickname();
        $user->email        = $data->email ?? null;
        $user->phone_number = $data->phoneNumber ?? null;
        $user->password     = $data->password ?? null;
        $user->avatar       = $data->avatar ?? null;
        $user->gender       = $data->gender ?? null;
        $user->birthday     = $data->birthday ?? null;
        // 验证是否允许注册

        // 验证信息


        return $user;
    }


    public function user()
    {

    }

    protected function buildUserName() : string
    {

        return Str::random(16);

    }

    protected function buildNickname() : string
    {
        return Str::random(6);
    }

}
