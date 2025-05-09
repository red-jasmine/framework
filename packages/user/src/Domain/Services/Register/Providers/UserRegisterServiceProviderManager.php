<?php

namespace RedJasmine\User\Domain\Services\Register\Providers;

use RedJasmine\Support\Helpers\Services\ServiceManager;


class UserRegisterServiceProviderManager extends ServiceManager
{

    protected const array PROVIDERS = [
        SmsRegisterServiceProvider::NAME      => SmsRegisterServiceProvider::class,
        PasswordRegisterServiceProvider::NAME => PasswordRegisterServiceProvider::class,
    ];
}
