<?php

namespace RedJasmine\Order\Application\Services\Payments\Commands;

use RedJasmine\Order\Domain\Data\OrderPaymentData;

class OrderPaymentPaidCommand extends OrderPaymentData
{

    public int $id;


}
