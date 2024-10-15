<?php

namespace RedJasmine\Payment\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Payment\Contracts\PaymentInterface;


/**
 * @mixin PaymentInterface
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'payment';
    }
}
