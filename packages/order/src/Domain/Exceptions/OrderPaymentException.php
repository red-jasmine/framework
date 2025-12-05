<?php

namespace RedJasmine\Order\Domain\Exceptions;

use RedJasmine\Support\Exceptions\BaseException;

class OrderPaymentException extends BaseException
{


    // 状态类限制错误
    public const  STATUS_NOT_ALLOW = 102510;


    protected static array $codes = [
        self::STATUS_NOT_ALLOW => '订单状态不允许操作',
    ];

}
