<?php

namespace RedJasmine\User\Domain\Services\Register;

use Illuminate\Support\Str;
use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Models\User;

/**
 * 注册服务
 */
class UserRegisterService
{

    public function register(UserData $data) : User
    {
        // 用户注册功能呢
        $user = User::make();

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


    protected function buildUserName() : string
    {

        return Str::uuid();

    }

    protected function buildNickname() : string
    {
        return Str::uuid();
    }

}
