<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderBuyerRemarksCommandHandler extends AbstractOrderRemarksCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::BUYER;
}
