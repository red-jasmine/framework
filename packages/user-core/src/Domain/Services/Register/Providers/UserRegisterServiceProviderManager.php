<?php

namespace RedJasmine\UserCore\Domain\Services\Register\Providers;

use RedJasmine\Support\Helpers\Services\ServiceManager;


class UserRegisterServiceProviderManager extends ServiceManager
{

    protected const  PROVIDERS = [
        SmsRegisterServiceProvider::NAME      => SmsRegisterServiceProvider::class,
        PasswordRegisterServiceProvider::NAME => PasswordRegisterServiceProvider::class,
    ];
}
