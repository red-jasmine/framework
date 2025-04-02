<?php

namespace RedJasmine\Interaction\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Interaction\Domain\Types\InteractionTypeManager;

class InteractionType extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return InteractionTypeManager::class;
    }

}