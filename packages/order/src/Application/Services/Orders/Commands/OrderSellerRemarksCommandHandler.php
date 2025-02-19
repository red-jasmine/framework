<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

class OrderSellerRemarksCommandHandler extends AbstractOrderRemarksCommandHandler
{
    protected TradePartyEnums $tradeParty = TradePartyEnums::SELLER;
}
