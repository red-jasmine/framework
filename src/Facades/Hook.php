<?php

namespace RedJasmine\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static register(string $hook, $pipeline)
 * @method static execute(string $hook, $passable, Closure $destination)
 */
class Hook extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'hook';
    }

}
