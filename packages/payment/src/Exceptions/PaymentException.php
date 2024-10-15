<?php

namespace RedJasmine\Payment\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class PaymentException extends AbstractException
{

    public const PAYMENT_STATUS_ERROR = 601010; // 支付状态错误
    public const PAYMENT_TIMEOUT      = 601011; // 支付超时

}
