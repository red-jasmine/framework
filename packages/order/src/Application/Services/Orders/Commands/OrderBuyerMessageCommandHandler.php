<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderBuyerMessageCommandHandler extends AbstractOrderMessageCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::BUYER;
}
