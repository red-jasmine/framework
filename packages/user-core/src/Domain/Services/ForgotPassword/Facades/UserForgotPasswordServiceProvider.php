<?php

namespace RedJasmine\UserCore\Domain\Services\ForgotPassword\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\Contracts\UserForgotPasswordServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\Providers\UserForgotPasswordServiceProviderManager;

/**
 * @see UserForgotPasswordServiceProviderManager
 * @method UserForgotPasswordServiceProviderInterface  create(string $name)
 */
class UserForgotPasswordServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\UserCore\\Domain\\Services\\ForgotPassword\\Providers\\UserForgotPasswordServiceProviderManager";

    }
}