<?php

namespace RedJasmine\User\Domain\Services\ForgotPassword\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\User\Domain\Services\ForgotPassword\Contracts\UserForgotPasswordServiceProviderInterface;
use RedJasmine\User\Domain\Services\ForgotPassword\Providers\UserForgotPasswordServiceProviderManager;

/**
 * @see UserForgotPasswordServiceProviderManager
 * @method UserForgotPasswordServiceProviderInterface  create(string $name)
 */
class UserForgotPasswordServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\User\\Domain\\Services\\ForgotPassword\\Providers\\UserForgotPasswordServiceProviderManager";

    }
}