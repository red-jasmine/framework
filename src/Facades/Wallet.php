<?php

namespace RedJasmine\Wallet\Facades;

use Illuminate\Support\Facades\Facade;

class Wallet extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'wallet';
    }
}
