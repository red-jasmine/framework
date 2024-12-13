<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Trade;

interface GatewayAdapterInterface
{
    public function init(ChannelApp $channelApp, ChannelProduct $channelProduct) : static;


    public function purchase(Trade $trade) : RequestInterface;


}
