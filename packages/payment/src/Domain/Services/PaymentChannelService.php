<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Support\Facades\Cache;
use Omnipay\Alipay\AopAppGateway;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Gateway\GatewayAdapter;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

/**
 * 支付渠道服务
 * 主要调度支付渠道的
 */
class PaymentChannelService
{

    /**
     * 创建交易单
     * @return void
     */
    public function createTrade(ChannelApp $channelApp, ChannelProduct $channelProduct, Trade $trade, Environment $environment)
    {
        // 设置为支付中
        // 根据选中的渠道，选择出签约的产品
        // 更具渠道、产品、环境、渠道应用、
        // TODO 如何只智能的转换 参数 更新 不同渠道 不同网关 设置不同的参数?
        // 只能每支持一个渠道 需要创建一个 渠道管处理器
        // 创建支付网关

        // 支付网关  由 渠道产品决定

        $adapter = GatewayAdapter::create($channelApp);
        // 创建网关
        $response = $adapter->init($channelApp, $channelProduct)
                            ->purchase($trade)->send();
        // 设置支付参数
        if($response->isSuccessful()){


        }


    }

}
