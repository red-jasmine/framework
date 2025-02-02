<?php

namespace RedJasmine\User\Domain\Services\Register;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class UserRegisterService
{


    public function register(UserRegisterData $data) : User
    {
        // 注册用户信息

        // 返回用户信息

        $user = User::make();


        return $user;
    }

}
