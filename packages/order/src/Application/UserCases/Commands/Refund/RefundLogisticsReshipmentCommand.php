<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Order\Domain\Data\LogisticsData;

class RefundLogisticsReshipmentCommand extends LogisticsData
{

    public int $id; // 退款单ID


}
