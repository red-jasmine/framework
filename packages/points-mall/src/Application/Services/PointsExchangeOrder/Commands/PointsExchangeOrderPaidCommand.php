<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderPaymentData;


class PointsExchangeOrderPaidCommand extends OrderPaymentData
{
    public string $orderNo;


}