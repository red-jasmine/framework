<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class SmsRegisterServiceProvider implements UserRegisterServiceProviderInterface
{

    public function __construct(
        protected UserReadRepositoryInterface $userReadRepository

    ) {
    }

    public const NAME = 'sms';


    public function register(UserRegisterData $data) : User
    {
        // TODO: Implement register() method.
    }


}
