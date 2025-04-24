<?php

namespace RedJasmine\User\Domain\Services\ForgotPassword\Providers;

use RedJasmine\Support\Helpers\Services\ServiceManager;
use RedJasmine\User\Domain\Services\ForgotPassword\Contracts\UserForgotPasswordServiceProviderInterface;

/**
 * @method UserForgotPasswordServiceProviderInterface  create(string $name)
 */
class UserForgotPasswordServiceProviderManager extends ServiceManager
{
    protected const array PROVIDERS = [
        SmsForgotPasswordServiceProvider::NAME => SmsForgotPasswordServiceProvider::class,
    ];

}