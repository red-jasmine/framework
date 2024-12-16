<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Gateway\Data\PurchaseResult;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

interface GatewayDriveInterface
{

    public function gateway(ChannelApp $channelApp, ?ChannelProduct $channelProduct = null) : static;

    public function purchase(Trade $trade, Environment $environment) : PurchaseResult;

}
