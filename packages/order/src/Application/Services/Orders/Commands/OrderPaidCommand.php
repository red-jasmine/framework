<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Data\OrderPaymentData;

class OrderPaidCommand extends OrderPaymentData
{
    public string $orderNo;

    public int $orderPaymentId;

}
