<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Trade;

/**
 * 渠道适配器
 */
class AlipayGatewayAdapter implements GatewayAdapterInterface
{

    protected GatewayInterface $gateway;

    public function init(ChannelApp $channelApp, ChannelProduct $channelProduct) : static
    {
        // TODO 产品 和网关的 映射器
        $this->gateway = Omnipay::create($channelProduct->code);

        return $this;
    }

    public function purchase(Trade $trade) : RequestInterface
    {

        return $this->gateway->purchase([]);
    }


}
