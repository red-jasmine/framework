<?php

namespace RedJasmine\Payment\Domain\Gateway;

use RedJasmine\Payment\Domain\Data\ChannelRefundData;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelPurchaseResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

interface GatewayDriveInterface
{

    public function gateway(PaymentChannelData $paymentChannelData) : static;

    public function purchase(Trade $trade, Environment $environment) : ChannelPurchaseResult;

    public function refund(Refund $refund) : ChannelRefundResult;

    public function refundQuery(Refund $refund) : ChannelRefundQueryResult;

    public function completePurchase(array $parameters = []) : ChannelTradeData;

    public function notifyResponse() : NotifyResponseInterface;


}
