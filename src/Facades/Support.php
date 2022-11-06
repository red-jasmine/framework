<?php

namespace RedJasmine\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Support extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'support';
    }
}
