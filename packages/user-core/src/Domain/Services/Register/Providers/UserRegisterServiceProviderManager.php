<?php

namespace RedJasmine\UserCore\Domain\Services\Register\Providers;

use RedJasmine\Support\Foundation\Manager\ServiceManager;


class UserRegisterServiceProviderManager extends ServiceManager
{

    protected const  PROVIDERS = [
        SmsRegisterServiceProvider::NAME      => SmsRegisterServiceProvider::class,
        PasswordRegisterServiceProvider::NAME => PasswordRegisterServiceProvider::class,
    ];
}
