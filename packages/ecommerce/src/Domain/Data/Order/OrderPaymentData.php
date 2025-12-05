<?php

namespace RedJasmine\Ecommerce\Domain\Data\Order;

use Cknow\Money\Money;
use RedJasmine\Support\Foundation\Data\Data;

class OrderPaymentData extends Data
{

    /**
     * 支付单 DI
     * @var string|null
     */
    public ?string $orderPaymentId = null;
    public Money   $amount;
    public string  $paymentType;
    public string  $paymentId;
    public ?string $paymentTime;
    /**
     * 支付渠道
     * @var string|null
     */
    public ?string $paymentChannel = null;
    /**
     * 支付渠道单号
     * @var string|null
     */
    public ?string $paymentChannelNo = null;
    /**
     * 支付方式
     * @var string|null
     */
    public ?string $paymentMethod = null;

    public ?string $message = null;
}
