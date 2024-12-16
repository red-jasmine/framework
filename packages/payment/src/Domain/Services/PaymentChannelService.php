<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
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
     * @return PurchaseResult
     * @throws PaymentException
     * @throws Throwable
     */
    public function createTrade(ChannelApp $channelApp, ChannelProduct $channelProduct, Trade $trade, Environment $environment) : PurchaseResult
    {

        $lock = $this->getLock($trade);

        if (!$lock->get()) {
            throw  PaymentException::newFromCodes(PaymentException::TRADE_PAYING);
        }
        // 支付网关适配器
        $gateway = GatewayDrive::create($channelApp->channel_code);

        try {
            return $gateway->gateway($channelApp, $channelProduct)->purchase($trade, $environment);
        } catch (Throwable $throwable) {
            throw  PaymentException::newFromCodes(PaymentException::TRADE_PAYING);
            throw $throwable;

        }


    }

    protected function getLock(Trade $trade) : Lock
    {

        $name = 'red-jasmine-payment:trade:' . $trade->id;

        return Cache::lock($name, 60);

    }


}
