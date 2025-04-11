<?php

namespace RedJasmine\User\Domain\Services\Register\Providers;

use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class EmailRegisterServiceProvider implements UserRegisterServiceProviderInterface
{

    public function __construct(
        protected UserReadRepositoryInterface $userReadRepository

    ) {
    }

    public const string NAME = 'email';

    public function preCheck(UserRegisterData $data) : UserData
    {
        // TODO: Implement preCheck() method.
    }


    public function register(UserRegisterData $data) : UserData
    {
        // 验证邮箱是否已经注册
    }


}
