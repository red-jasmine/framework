<?php

namespace RedJasmine\Interaction\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Interaction\Domain\Contracts\InteractionResourceInterface;
use RedJasmine\Interaction\Domain\Resources\InteractionResourceManager;


/**
 * @method InteractionResourceInterface create(string $name)
 */
class InteractionResource extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return InteractionResourceManager::class;
    }

}