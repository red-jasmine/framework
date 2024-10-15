<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 订单退款状态
 */
enum OrderRefundStatusEnum: string
{
    use EnumsHelper;

    case NIL = 'nil';
    case PART_REFUND = 'part_refund';
    case ALL_REFUND = 'all_refund';


    public static function labels() : array
    {
        return [
            self::NIL->value         => '',
            self::PART_REFUND->value => '部分退款',
            self::ALL_REFUND->value  => '全部退款',
        ];
    }
}
