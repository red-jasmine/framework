<?php

namespace RedJasmine\User\Domain\Services\Login\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Providers\UserLoginServiceProviderManager;


/**
 * @see UserLoginServiceProviderManager
 * @method UserLoginServiceProviderInterface create(string $name)
 */
class UserLoginServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\User\\Domain\\Services\\Login\\Providers\\UserLoginServiceProviderManager";
    }
}
