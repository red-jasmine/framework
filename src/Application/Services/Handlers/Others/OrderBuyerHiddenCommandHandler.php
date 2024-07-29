<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderBuyerHiddenCommandHandler extends OrderHiddenCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::BUYER;
}
