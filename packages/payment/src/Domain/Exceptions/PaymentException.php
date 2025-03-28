<?php

namespace RedJasmine\Payment\Domain\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class PaymentException extends AbstractException
{

    public const int PAYMENT_STATUS_ERROR  = 601010; // 支付状态错误
    public const int PAYMENT_TIMEOUT       = 601011; // 支付超时
    public const int CHANNEL_ROUTE         = 602010; // 渠道路由
    public const int CHANNEL_PRODUCT_ROUTE = 602011; // 渠道路由


    public const int TRADE_PAYING                = 603001; // 渠道路由
    public const int TRADE_STATUS_ERROR          = 603010; // 支付状态错误
    public const int TRADE_STATUS_NOT_ALLOW_PAID = 603011; // 支付状态错误
    public const int TRADE_AMOUNT_ERROR          = 603012; // 支付状态错误
    public const int TRADE_REFUND_AMOUNT_ERROR   = 603013; // 支付状态错误
    public const int TRADE_REFUND_TIME_ERROR     = 603014; // 支付状态错误


    public const int CHANNEL_REFUND_ERROR = 604020; // 支付状态错误
    public const int REFUND_STATUS_ERROR  = 605010; // 退款状态错误


    public const int CHANNEL_ERROR = 606020; // 渠道异常


    public const  int CHANNEL_REFUND_QUERY_ERROR = 604021; // 渠道查询退款状态错误


    public const int URL_SIGN = 604001; // 签名错误

}
