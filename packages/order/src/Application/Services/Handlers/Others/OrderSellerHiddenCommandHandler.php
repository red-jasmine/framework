<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderSellerHiddenCommandHandler extends OrderHiddenCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}
