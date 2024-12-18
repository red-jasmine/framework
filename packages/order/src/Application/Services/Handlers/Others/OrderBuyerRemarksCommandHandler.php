<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderBuyerRemarksCommandHandler extends AbstractOrderRemarksCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::BUYER;
}
