<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class PasswordLoginServiceProvider implements UserLoginServiceProviderInterface
{
    public const NAME = 'password';

    public function __construct(
        protected UserReadRepositoryInterface $userReadRepository

    ) {
    }

    public function login(UserLoginData $data) : User
    {
        // 按用户名称查询 用户
        $user = $this->userReadRepository->findByName($data->data['account']);

        // 验证密码
        $user->checkPassword($data->data['password']);

        // 返回用户信息

        return $user;
    }


}
