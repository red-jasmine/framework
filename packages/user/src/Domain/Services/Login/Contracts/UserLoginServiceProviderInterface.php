<?php

namespace RedJasmine\User\Domain\Services\Login\Contracts;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

interface UserLoginServiceProviderInterface
{
    public function login(UserLoginData $data) : User;
}
