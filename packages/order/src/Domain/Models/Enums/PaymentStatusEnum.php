<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 付款状态
 */
enum PaymentStatusEnum: string
{

    use EnumsHelper;


    case NIL = 'nil';

    case WAIT_PAY = 'wait_pay';
    // 支付中
    case PAYING = 'paying';
    // 部分支付
    case PART_PAY = 'part_pay';
    // 支付成功
    case PAID = 'paid';
    // 无需支付
    case NO_PAYMENT = 'no_payment';

    public static function labels() : array
    {
        return [
            self::NIL->value        => '',
            self::WAIT_PAY->value   => '待支付',
            self::PAYING->value     => '支付中',
            self::PART_PAY->value   => '部分支付',
            self::PAID->value       => '支付成功',
            self::NO_PAYMENT->value => '无需支付',
        ];

    }

    public static function colors() : array
    {
        return [
            self::NIL->value        => '',
            self::WAIT_PAY->value   => 'warning',
            self::PAYING->value     => 'warning',
            self::PART_PAY->value   => 'primary',
            self::PAID->value       => 'success',
            self::NO_PAYMENT->value => 'info',

        ];
    }
}
