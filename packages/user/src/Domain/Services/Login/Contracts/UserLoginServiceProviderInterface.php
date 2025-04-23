<?php

namespace RedJasmine\User\Domain\Services\Login\Contracts;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

interface UserLoginServiceProviderInterface
{

    public function captcha(UserLoginData $data);

    public function login(UserLoginData $data) : User;
}
