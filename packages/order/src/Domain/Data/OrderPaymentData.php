<?php

namespace RedJasmine\Order\Domain\Data;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Data\Data;

class OrderPaymentData extends Data
{
    public Money   $amount;
    public string  $paymentType;
    public string  $paymentId;
    public ?string $paymentTime;
    public ?string $paymentChannel = null;
    /**
     * 支付渠道单号
     * @var string|null
     */
    public ?string $paymentChannelNo = null;
    public ?string $paymentMethod    = null;

    public ?string $message = null;
}
