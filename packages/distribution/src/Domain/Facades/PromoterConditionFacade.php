<?php

namespace RedJasmine\Distribution\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Distribution\Domain\Contracts\PromoterConditionInterface;
use RedJasmine\Distribution\Domain\Services\Conditions\PromoterConditionProviderManager;

/**
 * @method  PromoterConditionInterface create(string $name)
 */
class PromoterConditionFacade extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return PromoterConditionProviderManager::class;
    }
}
