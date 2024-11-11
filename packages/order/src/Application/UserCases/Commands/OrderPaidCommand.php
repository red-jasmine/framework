<?php

namespace RedJasmine\Order\Application\UserCases\Commands;

use DateTime;
use RedJasmine\Order\Domain\Data\OrderPaymentData;
use RedJasmine\Support\Data\Data;

class OrderPaidCommand extends OrderPaymentData
{

    public int     $id;
    public int     $orderPaymentId;

}
