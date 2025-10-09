<?php

namespace RedJasmine\UserCore\Domain\Services\Register\Contracts;

use RedJasmine\UserCore\Domain\Data\UserData;
use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\Register\Data\UserRegisterData;

interface UserRegisterServiceProviderInterface
{
    public function init(BaseUserRepositoryInterface $repository, string $guard) : static;

    // 预校验步骤
    public function captcha(UserRegisterData $data) : UserData;

    // 注册步骤
    public function register(UserRegisterData $data) : UserData;
}
