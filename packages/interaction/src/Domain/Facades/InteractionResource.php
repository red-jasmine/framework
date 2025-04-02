<?php

namespace RedJasmine\Interaction\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Interaction\Domain\Resources\InteractionResourceManager;


class InteractionResource extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return InteractionResourceManager::class;
    }

}