<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

class RefundAgreeRefundCommand extends Data
{
    public string $refundNo; // 退款单号

    public ?Money $amount = null;
}
