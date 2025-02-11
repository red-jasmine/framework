<?php

namespace RedJasmine\User\Domain\Services\Register\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Providers\UserRegisterServiceProviderManager;

/**
 * @see UserRegisterServiceProviderManager
 * @method UserRegisterServiceProviderInterface create(string $name)
 */
class UserRegisterServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\User\\Domain\\Services\\Register\\Providers\\UserRegisterServiceProviderManager";
    }
}
