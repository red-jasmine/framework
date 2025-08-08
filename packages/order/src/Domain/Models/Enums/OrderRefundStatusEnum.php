<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 订单退款状态
 */
enum OrderRefundStatusEnum: string
{
    use EnumsHelper;

    case  PARTIAL = 'partial'; // 部分退款

    case  FULL = 'full'; // 全部退款


    public static function labels() : array
    {
        return [
            self::FULL->value    => __('red-jasmine-order::order.enums.order_refund_status.full'),
            self::PARTIAL->value => __('red-jasmine-order::order.enums.order_refund_status.partial'),
        ];
    }
}
