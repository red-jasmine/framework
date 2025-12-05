<?php

namespace RedJasmine\UserCore\Domain\Services\ForgotPassword\Providers;

use RedJasmine\Support\Foundation\Manager\ServiceManager;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\Contracts\UserForgotPasswordServiceProviderInterface;

/**
 * @method UserForgotPasswordServiceProviderInterface  create(string $name)
 */
class UserForgotPasswordServiceProviderManager extends ServiceManager
{
    protected const  PROVIDERS = [
        SmsForgotPasswordServiceProvider::NAME => SmsForgotPasswordServiceProvider::class,
    ];

}