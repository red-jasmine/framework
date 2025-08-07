<?php

namespace RedJasmine\Shopping\Application\Services\Orders\Commands;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderPaymentData;

/**
 * 支付结果命令
 */
class PaidCommand extends OrderPaymentData
{
    public string $orderNo;
    public string $orderPaymentId;

}