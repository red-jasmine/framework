<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class RefundBuyerRemarksCommandHandler extends AbstractRefundRemarksCommandHandler
{

    protected TradePartyEnums $tradeParty = TradePartyEnums::BUYER;
}
