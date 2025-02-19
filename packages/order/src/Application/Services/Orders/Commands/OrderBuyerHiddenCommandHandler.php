<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderBuyerHiddenCommandHandler extends AbstractOrderHiddenCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::BUYER;
}
