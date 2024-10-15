<?php

namespace RedJasmine\Payment\Enums;

enum PaymentStatus: int
{
    case  NOT_PAY = 0; // 等待支付
    case  SUCCESS = 1; // 成功有效订单
    case  REFUND = 2; // 全款退
    case  CLOSED = 3; // 操时关闭
    case  PAYING = 4; // 支付中
    case  FAIL = 5; // 支付失败

    public static function options() : array
    {
        return [

            self::NOT_PAY->value => '等待支付',
            self::SUCCESS->value => '成功',
            self::CLOSED->value  => '已关闭',
            self::REFUND->value  => '已退款',
            self::PAYING->value  => '支付中',
            self::FAIL->value    => '失败',
        ];
    }


}
