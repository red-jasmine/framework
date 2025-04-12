<?php

namespace RedJasmine\User\Domain\Services\Register\Providers;

use RedJasmine\Support\Helpers\Services\ServiceManager;


class UserRegisterServiceProviderManager extends ServiceManager
{

    protected const array PROVIDERS = [
        NameRegisterServiceProvider::NAME     => NameRegisterServiceProvider::class,
        PasswordRegisterServiceProvider::NAME => PasswordRegisterServiceProvider::class,

        // 短信注册服务
        // 社交账号注册服务
    ];
}
