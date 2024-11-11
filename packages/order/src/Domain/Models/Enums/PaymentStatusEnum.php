<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 付款状态
 */
enum PaymentStatusEnum: string
{

    use EnumsHelper;


    case WAIT_PAY = 'wait_pay';
    // 支付中
    case PAYING = 'paying';
    // 部分支付
    case PART_PAY = 'part_pay';
    // 支付成功
    case PAID = 'paid';
    // 无需支付
    case NO_PAYMENT = 'no_payment';

    case FAIL = 'fail';

    public static function labels() : array
    {
        return [
            self::WAIT_PAY->value   => __('red-jasmine-order::common.enums.payment_status.wait_pay'),
            self::PAYING->value     => __('red-jasmine-order::common.enums.payment_status.paying'),
            self::PART_PAY->value   => __('red-jasmine-order::common.enums.payment_status.part_pay'),
            self::PAID->value       => __('red-jasmine-order::common.enums.payment_status.paid'),
            self::NO_PAYMENT->value => __('red-jasmine-order::common.enums.payment_status.no_payment'),
        ];

    }

    public static function colors() : array
    {
        return [

            self::WAIT_PAY->value   => 'warning',
            self::PAYING->value     => 'warning',
            self::PART_PAY->value   => 'primary',
            self::PAID->value       => 'success',
            self::NO_PAYMENT->value => 'info',

        ];
    }
}
