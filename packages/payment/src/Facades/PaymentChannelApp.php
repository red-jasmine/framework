<?php

namespace RedJasmine\Payment\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentChannelApp extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'payment.channel.app';
    }

}
