<?php

namespace RedJasmine\Order\Domain\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class RefundException extends AbstractException
{


    public const  REFUND_AMOUNT_OVERFLOW = 111001;

    public const  REFUND_STATUS_NOT_ALLOW = 211510;

    public const  REFUND_TYPE_NOT_ALLOW = 311520;


    protected static array $codes = [
        self::REFUND_STATUS_NOT_ALLOW => '售后状态不支持操作',
        self::REFUND_TYPE_NOT_ALLOW   => '售后类型不支持操作',
        self::REFUND_AMOUNT_OVERFLOW  => '退款金额超出',
    ];


}
