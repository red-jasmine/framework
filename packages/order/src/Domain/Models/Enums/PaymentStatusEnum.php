<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 付款状态
 */
enum PaymentStatusEnum: string
{

    use EnumsHelper;


    case WAITING = 'waiting';
    // 支付中
    case PAYING = 'paying';
    // 首付款
    case PARTIAL = 'partial';
    // 支付成功
    case PAID = 'paid';
    // 无需支付
    case EXEMPT = 'exempt';

    case FAIL = 'fail';

    public static function labels() : array
    {
        return [
            self::WAITING->value => __('red-jasmine-order::common.enums.payment_status.waiting'),
            self::PAYING->value  => __('red-jasmine-order::common.enums.payment_status.paying'),
            self::PARTIAL->value => __('red-jasmine-order::common.enums.payment_status.partial'),
            self::PAID->value    => __('red-jasmine-order::common.enums.payment_status.paid'),
            self::EXEMPT->value  => __('red-jasmine-order::common.enums.payment_status.exempt'),
        ];

    }

    public static function colors() : array
    {
        return [

            self::WAITING->value => 'warning',
            self::PAYING->value  => 'warning',
            self::PARTIAL->value => 'primary',
            self::PAID->value    => 'success',
            self::EXEMPT->value  => 'info',

        ];
    }
}
