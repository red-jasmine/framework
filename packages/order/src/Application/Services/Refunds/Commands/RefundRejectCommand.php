<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class RefundRejectCommand extends Data
{
    public string $refundNo; // 退款单ID


    public string $reason = '';
}
