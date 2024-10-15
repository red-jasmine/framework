<?php

namespace RedJasmine\Order\Application\Services\Handlers\Others;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderSellerRemarksCommandHandler extends OrderRemarksCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}
