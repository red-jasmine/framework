<?php

namespace RedJasmine\Order\Application\Services\Payments\Commands;

use RedJasmine\Order\Domain\Data\OrderPaymentData;

class OrderPaymentFailCommand extends OrderPaymentData
{

    public int $id;


}
