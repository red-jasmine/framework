<?php

namespace RedJasmine\UserCore\Domain\Services\Login\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\UserCore\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\Login\Providers\UserLoginServiceProviderManager;


/**
 * @see UserLoginServiceProviderManager
 * @method UserLoginServiceProviderInterface create(string $name)
 */
class UserLoginServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\UserCore\\Domain\\Services\\Login\\Providers\\UserLoginServiceProviderManager";
    }
}
