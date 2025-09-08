<?php

namespace RedJasmine\User\Domain\Services\Register\Contracts;

use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

interface UserRegisterServiceProviderInterface
{
    public function init(UserRepositoryInterface $repository, string $guard) : static;

    // 预校验步骤
    public function captcha(UserRegisterData $data) : UserData;

    // 注册步骤
    public function register(UserRegisterData $data) : UserData;
}
