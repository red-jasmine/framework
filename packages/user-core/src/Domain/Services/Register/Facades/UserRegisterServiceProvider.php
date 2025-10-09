<?php

namespace RedJasmine\UserCore\Domain\Services\Register\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\UserCore\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\Register\Providers\UserRegisterServiceProviderManager;

/**
 * @see UserRegisterServiceProviderManager
 * @method UserRegisterServiceProviderInterface create(string $name)
 */
class UserRegisterServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\UserCore\\Domain\\Services\\Register\\Providers\\UserRegisterServiceProviderManager";
    }
}
