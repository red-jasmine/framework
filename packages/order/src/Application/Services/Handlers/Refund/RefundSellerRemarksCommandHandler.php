<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class RefundSellerRemarksCommandHandler extends AbstractRefundRemarksCommandHandler
{

    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}