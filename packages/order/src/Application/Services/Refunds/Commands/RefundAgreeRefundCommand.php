<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

class RefundAgreeRefundCommand extends Data
{
    public int $id; // 退款单ID

    public ?Amount $amount;
}
