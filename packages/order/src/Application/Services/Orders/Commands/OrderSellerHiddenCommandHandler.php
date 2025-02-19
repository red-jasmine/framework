<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderSellerHiddenCommandHandler extends AbstractOrderHiddenCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}
