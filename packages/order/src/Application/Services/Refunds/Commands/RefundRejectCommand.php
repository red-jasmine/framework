<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Support\Data\Data;

class RefundRejectCommand extends Data
{
    public int $id; // 退款单ID


    public string $reason = '';
}
