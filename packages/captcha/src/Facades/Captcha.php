<?php

namespace RedJasmine\Captcha\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @mixin \RedJasmine\Captcha\Captcha
 */
class Captcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'captcha';
    }
}
