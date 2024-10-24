<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderSellerMessageCommandHandler extends AbstractOrderMessageCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}
