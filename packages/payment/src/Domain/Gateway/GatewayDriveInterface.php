<?php

namespace RedJasmine\Payment\Domain\Gateway;

use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelPurchaseResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleReceiverQuery;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleReceiverQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelTransferQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelTransferResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

interface GatewayDriveInterface
{
    // TODO 这里传输给网关的 应该是 DTO 不支持修改 操作

    public function gateway(PaymentChannelData $paymentChannelData) : static;

    public function purchase(Trade $trade, Environment $environment) : ChannelPurchaseResult;

    public function refund(Refund $refund) : ChannelRefundResult;

    public function refundQuery(Refund $refund) : ChannelRefundQueryResult;

    public function completePurchase(array $parameters = []) : ChannelTradeData;

    public function notifyResponse() : NotifyResponseInterface;

    public function transfer(Transfer $transfer) : ChannelTransferResult;

    public function transferQuery(Transfer $transfer) : ChannelTransferQueryResult;

    public function bindSettleReceiver(SettleReceiver $settleReceiver) : ChannelResult;

    public function unbindSettleReceiver(SettleReceiver $settleReceiver) : ChannelResult;

    public function querySettleReceivers(
        ChannelSettleReceiverQuery $query = new ChannelSettleReceiverQuery
    ) : ChannelSettleReceiverQueryResult;

    public function settle(Settle $settle):ChannelSettleResult;
}
