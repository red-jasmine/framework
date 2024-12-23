<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Support\Facades\URL;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\Trade;

class PaymentUrlService
{

    protected string $returnUrlRouteName = 'payment.payer.trades.show';
    protected string $notifyUrlRouteName = 'payment.notify.notify';


    /**
     * @param array $parameters
     * @return void
     * @throws PaymentException
     */
    public function validSignature(array $parameters) : void
    {

        $signature = $parameters['signature'] ?? '';
        unset($parameters['signature']);
        $sign = $this->signature($parameters);
        if ($sign !== $signature) {
            throw new PaymentException('invalid signature', PaymentException::CHANNEL_PRODUCT_ROUTE);
        }
    }

    public function notifyUrl(ChannelApp $channelApp) : string
    {
        $parameters              = [
            'channel' => $channelApp->channel_code,
            'app'     => $channelApp->id,
            'time'    => time()
        ];
        $parameters['signature'] = $this->signature($parameters);

        return URL::route($this->notifyUrlRouteName, $parameters);
    }

    protected function signature($data) : string
    {
        ksort($data);
        return hash_hmac(
            'sha256',
            http_build_query($data),
            config('app.key')
        );

    }


    public function returnUrl(Trade $trade) : string
    {

        $parameters              = [
            'id'   => $trade->trade_no,
            'time' => time()
        ];
        $parameters['signature'] = $this->signature($parameters);
        return URL::route($this->returnUrlRouteName, $parameters);
    }

}
