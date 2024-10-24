<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderBuyerMessageCommandHandler extends AbstractOrderMessageCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::BUYER;
}
