<?php

namespace RedJasmine\Interaction\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Interaction\Domain\Contracts\InteractionTypeInterface;
use RedJasmine\Interaction\Domain\Types\InteractionTypeManager;

/**
 * @method InteractionTypeInterface create(string $name)
 */
class InteractionType extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return InteractionTypeManager::class;
    }

}