<?php

namespace RedJasmine\Socialite\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RedJasmine\Socialite\Socialite
 */
class Socialite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \RedJasmine\Socialite\Socialite::class;
    }
}
