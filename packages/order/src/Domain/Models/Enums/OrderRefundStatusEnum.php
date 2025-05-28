<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 订单退款状态
 */
enum OrderRefundStatusEnum: string
{
    use EnumsHelper;

    case  FULL_REFUND = 'full_refund';

    case  PARTIAL_REFUND = 'partial_refund';

    public static function labels():array
    {
        return  [
            self::FULL_REFUND->value => __('red-jasmine-order::order.enums.order_refund_status.full_refund'),
            self::PARTIAL_REFUND->value => __('red-jasmine-order::order.enums.order_refund_status.partial_refund'),
        ];
    }
}
