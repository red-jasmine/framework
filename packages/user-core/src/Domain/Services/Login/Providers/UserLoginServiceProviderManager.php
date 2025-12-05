<?php

namespace RedJasmine\UserCore\Domain\Services\Login\Providers;

use RedJasmine\Support\Foundation\Manager\ServiceManager;
use RedJasmine\UserCore\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;

/**
 * 用户登陆管理器
 * @method  UserLoginServiceProviderInterface create(string $name)
 */
class UserLoginServiceProviderManager extends ServiceManager
{

    protected const  PROVIDERS = [
        SmsLoginServiceProvider::NAME       => SmsLoginServiceProvider::class,
        PasswordLoginServiceProvider::NAME  => PasswordLoginServiceProvider::class,
        SocialiteLoginServiceProvider::NAME => SocialiteLoginServiceProvider::class,
    ];

}
