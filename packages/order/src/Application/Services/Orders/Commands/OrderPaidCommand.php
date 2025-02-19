<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Domain\Data\OrderPaymentData;

class OrderPaidCommand extends OrderPaymentData
{

    public int     $id;
    public int     $orderPaymentId;

}
