<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;

class RefundAgreeRefundCommand extends Data
{
    public int $id; // 退款单ID

    public ?MoneyOld $amount;
}
