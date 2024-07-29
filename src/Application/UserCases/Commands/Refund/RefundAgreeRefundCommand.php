<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Support\Data\Data;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;

class RefundAgreeRefundCommand extends Data
{
    public int $rid; // 退款单ID

    public ?Amount $amount;
}
