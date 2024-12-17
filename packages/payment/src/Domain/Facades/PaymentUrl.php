<?php

namespace RedJasmine\Payment\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Services\PaymentUrlService;


/**
 * @method string notifyUrl(ChannelApp $channelApp)
 * @method string returnUrl(Trade $trade)
 */
class PaymentUrl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return PaymentUrlService::class;
    }
}
