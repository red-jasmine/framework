<?php

namespace RedJasmine\Payment\Domain\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class PaymentException extends AbstractException
{

    public const int PAYMENT_STATUS_ERROR  = 601010; // 支付状态错误
    public const int PAYMENT_TIMEOUT       = 601011; // 支付超时
    public const int CHANNEL_ROUTE         = 602010; // 渠道路由
    public const int CHANNEL_PRODUCT_ROUTE = 602011; // 渠道路由

}
