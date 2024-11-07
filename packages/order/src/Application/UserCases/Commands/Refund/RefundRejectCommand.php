<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Support\Data\Data;

class RefundRejectCommand extends Data
{
    public int $rid; // 退款单ID


    public string $reason = '';
}
