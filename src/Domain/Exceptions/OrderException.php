<?php

namespace RedJasmine\Order\Domain\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class OrderException extends AbstractException
{


    // 状态类限制错误
    public const ORDER_STATUS_NOT_ALLOW    = 102510;
    public const PAYMENT_STATUS_NOT_ALLOW  = 102511;
    public const SHIPPING_STATUS_NOT_ALLOW = 102512;
    // 类型错误
    public const SHIPPING_TYPE_NOT_ALLOW = 102520;

    // 其他错误
    public const PROGRESS_NOT_ALLOW_LESS = 102610;
    // 发货错误
    public const NO_EFFECTIVE_SHIPPING = 102611;

    protected static array $codes = [
        self::SHIPPING_TYPE_NOT_ALLOW   => '发货类型限制操作',
        self::ORDER_STATUS_NOT_ALLOW    => '订单状态限制操作',
        self::PAYMENT_STATUS_NOT_ALLOW  => '支付状态限制操作',
        self::SHIPPING_STATUS_NOT_ALLOW => '发货状态限制操作',
        self::PROGRESS_NOT_ALLOW_LESS   => '进度不允许小于之前的值',
        self::NO_EFFECTIVE_SHIPPING     => '没有有效发货',
    ];

}
