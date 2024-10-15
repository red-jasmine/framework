<?php

namespace RedJasmine\FilamentCard\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RedJasmine\FilamentCard\FilamentCard
 */
class FilamentCard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \RedJasmine\FilamentCard\FilamentCard::class;
    }
}
