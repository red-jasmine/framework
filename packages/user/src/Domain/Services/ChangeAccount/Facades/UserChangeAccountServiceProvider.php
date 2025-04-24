<?php

namespace RedJasmine\User\Domain\Services\ChangeAccount\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\User\Domain\Services\ChangeAccount\Contracts\UserChannelAccountServiceProviderInterface;
use RedJasmine\User\Domain\Services\ChangeAccount\Providers\UserChangeAccountServiceProviderManager;

/**
 * @see UserChangeAccountServiceProviderManager
 * @method UserChannelAccountServiceProviderInterface create(string $name)
 */
class UserChangeAccountServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\User\\Domain\\Services\\ChangeAccount\\Providers\\UserChangeAccountServiceProviderManager";

    }
}