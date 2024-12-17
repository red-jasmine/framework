<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\PurchaseResult;
use RedJasmine\Payment\Domain\Gateway\GatewayDrive;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use Throwable;

/**
 * 支付渠道服务
 * 主要调度支付渠道的
 */
class PaymentChannelService
{


    protected function getNotifyUrl(string $channelCode) : string
    {
        return URL::signedRoute('payment.notify.notify', [ 'channel' => $channelCode ]);
    }


    /**
     * 创建交易单
     * @param ChannelApp $channelApp
     * @param ChannelProduct $channelProduct
     * @param Trade $trade
     * @param Environment $environment
     * @return ChannelResult
     * @throws PaymentException
     * @throws Throwable
     */
    public function createTrade(ChannelApp $channelApp, ChannelProduct $channelProduct, Trade $trade, Environment $environment) : ChannelResult
    {

        $lock = $this->getLock($trade);

//        if (!$lock->get()) {
//            throw  PaymentException::newFromCodes(PaymentException::TRADE_PAYING);
//        }
        // 支付网关适配器
        $gateway = GatewayDrive::create($channelApp->channel_code);

        // 设置支付渠道信息
        $paymentChannelData                 = new  PaymentChannelData;
        $paymentChannelData->channelApp     = $channelApp;
        $paymentChannelData->channelProduct = $channelProduct;

        try {
            return $gateway->gateway($paymentChannelData)->purchase($trade, $environment);
        } catch (Throwable $throwable) {
            throw $throwable;
            throw  PaymentException::newFromCodes(PaymentException::TRADE_PAYING);
        }


    }

    protected function getLock(Trade $trade) : Lock
    {

        $name = 'red-jasmine-payment:trade:' . $trade->id;
        return Cache::lock($name, 60);
    }


}
