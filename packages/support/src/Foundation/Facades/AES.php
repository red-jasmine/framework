<?php

namespace RedJasmine\Support\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string encryptString($value)
 * @method static string decryptString($value)
 */
class AES extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'aes';
    }
}
