<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Support\Facades\URL;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\Trade;

class PaymentUrlService
{

    protected string $returnUrlRouteName = 'payment.payer.trades.show';
    protected string $notifyUrlRouteName = 'payment.notify.notify';

    public function notifyUrl(ChannelApp $channelApp) : string
    {
        return URL::signedRoute($this->notifyUrlRouteName, [ 'channel' => $channelApp->channel_code ]);
    }


    public function returnUrl(Trade $trade) : string
    {
        return URL::signedRoute($this->returnUrlRouteName, [ 'id' => $trade->id ]);
    }

}
