<?php

namespace RedJasmine\UserCore\Domain\Services\Login\Contracts;

use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\Login\Data\UserLoginData;

interface UserLoginServiceProviderInterface
{
    public function init(UserRepositoryInterface $repository, string $guard) : static;

    public function captcha(UserLoginData $data);

    public function login(UserLoginData $data) : User;
}
