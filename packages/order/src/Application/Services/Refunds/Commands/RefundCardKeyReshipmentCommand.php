<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Domain\Data\CardKeyData;

class RefundCardKeyReshipmentCommand extends CardKeyData
{

    public string $refundNo; // 退款单号


}
