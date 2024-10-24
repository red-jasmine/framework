<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderSellerHiddenCommandHandler extends AbstractOrderHiddenCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}
