<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\Support\Helpers\Services\ServiceManager;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;

/**
 * 用户登陆管理器
 * @method  UserLoginServiceProviderInterface create(string $name)
 */
class UserLoginServiceProviderManager extends ServiceManager
{

    protected const array PROVIDERS = [
        SmsLoginServiceProvider::NAME       => SmsLoginServiceProvider::class,
        PasswordLoginServiceProvider::NAME  => PasswordLoginServiceProvider::class,
        SocialiteLoginServiceProvider::NAME => SocialiteLoginServiceProvider::class,
    ];

}
