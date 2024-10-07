<?php

namespace Redjasmine\FilamentProduct\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Redjasmine\FilamentProduct\FilamentProduct
 */
class FilamentProduct extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Redjasmine\FilamentProduct\FilamentProduct::class;
    }
}
