<?php

namespace RedJasmine\User\Domain\Services\Register\Providers;

use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class SocialiteRegisterServiceProvider implements UserRegisterServiceProviderInterface
{


    public function __construct(
        protected UserReadRepositoryInterface $userReadRepository

    ) {
    }

    public const string NAME = 'socialite';


    public function captcha(UserRegisterData $data) : UserData
    {
        // 获取第三方用户
        // TODO: Implement preCheck() method.
    }


    public function register(UserRegisterData $data) : UserData
    {

    }


}
