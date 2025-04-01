<?php

namespace RedJasmine\Interaction\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Interaction\Domain\InteractionManager;

class Interaction extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return InteractionManager::class;
    }

}