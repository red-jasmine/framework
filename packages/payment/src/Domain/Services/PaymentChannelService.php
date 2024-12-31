<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use RedJasmine\Payment\Domain\Data\ChannelRefundData;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\GatewayDrive;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Refund;
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
            $channelPurchaseResult = $gateway->gateway($paymentChannelData)->purchase($trade, $environment);

            if ($channelPurchaseResult->isSuccessFul() === false) {
                throw new PaymentException($channelPurchaseResult->getMessage(), PaymentException::TRADE_PAYING);
            }


            $channelTradeData                     = new ChannelTradeData();
            $channelTradeData->tradeNo            = $trade->trade_no;
            $channelTradeData->amount             = $trade->amount;
            $channelTradeData->channelCode        = $channelApp->channel_code;
            $channelTradeData->channelProductCode = $channelProduct->code;
            $channelTradeData->channelAppId       = $channelApp->channel_app_id;
            $channelTradeData->channelMerchantId  = $channelApp->channel_merchant_id;
            $channelTradeData->channelTradeNo     = $channelPurchaseResult->getTradeNo();
            $channelTradeData->sceneCode          = $environment->scene->value;
            $channelTradeData->methodCode         = $environment->method;
            $channelTradeData->paymentTrigger     = $channelPurchaseResult->paymentTrigger;
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
        $gateway = GatewayDrive::create($channelApp->channel_code);
        // 设置支付渠道信息
        $paymentChannelData = new  PaymentChannelData;

        $paymentChannelData->channelApp = $channelApp;

        return $gateway->gateway($paymentChannelData)->completePurchase($data);
    }


    /**
     * @param ChannelApp $channelApp
     * @param Refund $refund
     * @return bool
     * @throws PaymentException
     */
    public function refund(ChannelApp $channelApp, Refund $refund) : bool
    {

        // 支付网关适配器
        $gateway = GatewayDrive::create($channelApp->channel_code);

        $paymentChannelData             = new  PaymentChannelData;
        $paymentChannelData->channelApp = $channelApp;

        $channelResult = $gateway->gateway($paymentChannelData)->refund($refund);
        if (!$channelResult->isSuccessFul()) {
            // 渠道退款异常
            throw new PaymentException($channelResult->getMessage(), PaymentException::CHANNEL_REFUND_ERROR);
        }
        return true;
    }

    public function refundQuery(ChannelApp $channelApp, Refund $refund) : ChannelRefundData
    {
        // 支付网关适配器
        $gateway = GatewayDrive::create($channelApp->channel_code);

        $paymentChannelData             = new  PaymentChannelData;
        $paymentChannelData->channelApp = $channelApp;

        return $gateway->gateway($paymentChannelData)->refundQuery($refund);
    }

    public function notifyResponse(ChannelApp $channelApp) : NotifyResponseInterface
    {

        $gateway = GatewayDrive::create($channelApp->channel_code);
        // 设置支付渠道信息
        $paymentChannelData = new  PaymentChannelData;

        $paymentChannelData->channelApp = $channelApp;

        return $gateway->gateway($paymentChannelData)->notifyResponse();

    }


}
