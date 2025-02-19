<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Domain\Data\CardKeyData;

class RefundCardKeyReshipmentCommand extends CardKeyData
{

    public int $id; // 退款单ID


}
