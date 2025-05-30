<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Domain\Data\LogisticsData;

class RefundReturnGoodsCommand extends LogisticsData
{

    public string $refundNo; // 退款单ID


}
