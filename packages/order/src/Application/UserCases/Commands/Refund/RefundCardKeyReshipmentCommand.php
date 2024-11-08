<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Order\Domain\Data\CardKeyData;

class RefundCardKeyReshipmentCommand extends CardKeyData
{

    public int $rid; // 退款单ID


}
