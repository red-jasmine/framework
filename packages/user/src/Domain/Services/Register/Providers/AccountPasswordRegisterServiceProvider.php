<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class AccountPasswordRegisterServiceProvider implements UserRegisterServiceProviderInterface
{

    public function __construct(
        protected UserReadRepositoryInterface $userReadRepository

    ) {
    }

    public const string NAME = 'account-password';

    public function preCheck(UserRegisterData $data) : UserData
    {
        // TODO: Implement preCheck() method.
    }


    public function register(UserRegisterData $data) : UserData
    {
        // 验证邮箱是否已经注册
    }


}
