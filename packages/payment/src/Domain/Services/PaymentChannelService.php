<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
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


    /**
     * 创建交易单
     * @param ChannelApp $channelApp
     * @param ChannelProduct $channelProduct
     * @param Trade $trade
     * @param Environment $environment
     * @return ChannelTradeData
     * @throws PaymentException
     */
    public function purchase(
        ChannelApp     $channelApp,
        ChannelProduct $channelProduct,
        Trade          $trade,
        Environment    $environment) : ChannelTradeData
    {
        // 支付网关适配器
        $gateway = GatewayDrive::create($channelApp->channel_code);
        // 设置支付渠道信息
        $paymentChannelData                 = new  PaymentChannelData;
        $paymentChannelData->channelApp     = $channelApp;
        $paymentChannelData->channelProduct = $channelProduct;

        try {
            $channelResult = $gateway->gateway($paymentChannelData)->purchase($trade, $environment);

            if ($channelResult->isSuccessFul() === false) {
                throw new PaymentException($channelResult->getMessage(), PaymentException::TRADE_PAYING);
            }


            $channelTradeData                     = new ChannelTradeData();
            $channelTradeData->merchantId         = $trade->merchant_id;
            $channelTradeData->merchantAppId      = $trade->merchant_app_id;
            $channelTradeData->id                 = $trade->id;
            $channelTradeData->amount             = $trade->amount;
            $channelTradeData->channelCode        = $channelApp->channel_code;
            $channelTradeData->channelProductCode = $channelProduct->code;
            $channelTradeData->channelAppId       = $channelApp->channel_app_id;
            $channelTradeData->channelMerchantId  = $channelApp->channel_merchant_id;
            $channelTradeData->channelTradeNo     = $channelResult->getTradeNo();
            $channelTradeData->sceneCode          = $environment->scene->value;
            $channelTradeData->methodCode         = $environment->method;
            $channelTradeData->purchaseResult     = $channelResult->getResult();
            return $channelTradeData;
        } catch (Throwable $throwable) {

            report($throwable);
            throw new PaymentException($throwable->getMessage(), PaymentException::TRADE_PAYING);
        }


    }

    protected function getLock(Trade $trade) : Lock
    {

        $name = 'red-jasmine-payment:trade:' . $trade->id;
        return Cache::lock($name, 60);
    }


    public function completePurchase(ChannelApp $channelApp, array $data) : ChannelTradeData
    {
        // 支付网关适配器
        // 支付网关适配器
        $gateway = GatewayDrive::create($channelApp->channel_code);
        // 设置支付渠道信息
        $paymentChannelData = new  PaymentChannelData;

        $paymentChannelData->channelApp = $channelApp;

        return $gateway->gateway($paymentChannelData)->completePurchase($data);
    }


}
