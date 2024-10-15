<?php

namespace RedJasmine\Address\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \RedJasmine\Address\Address
 */
class Address extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'address';
    }
}
