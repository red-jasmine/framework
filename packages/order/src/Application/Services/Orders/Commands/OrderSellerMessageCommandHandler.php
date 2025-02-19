<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderSellerMessageCommandHandler extends AbstractOrderMessageCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}
