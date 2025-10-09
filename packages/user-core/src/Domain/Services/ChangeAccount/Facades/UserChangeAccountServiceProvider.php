<?php

namespace RedJasmine\UserCore\Domain\Services\ChangeAccount\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Contracts\UserChannelAccountServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Providers\UserChangeAccountServiceProviderManager;

/**
 * @see UserChangeAccountServiceProviderManager
 * @method UserChannelAccountServiceProviderInterface create(string $name)
 */
class UserChangeAccountServiceProvider extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return "RedJasmine\\UserCore\\Domain\\Services\\ChangeAccount\\Providers\\UserChangeAccountServiceProviderManager";

    }
}