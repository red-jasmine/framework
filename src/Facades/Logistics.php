<?php

namespace RedJasmine\Logistics\Facades;

use Illuminate\Support\Facades\Facade;

class Logistics extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'logistics';
    }
}
