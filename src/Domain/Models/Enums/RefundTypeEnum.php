<?php

namespace RedJasmine\Order\Domain\Models\Enums;


use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 退款类型
 */
enum RefundTypeEnum: string
{
    use EnumsHelper;

    // 仅退款
    case  REFUND = 'refund';
    // 退货退款
    case  RETURN_GOODS_REFUND = 'return_goods_refund';
    // 换货
    case  EXCHANGE = 'exchange';
    // 维修
    case  SERVICE = 'service';
    // 补寄
    case  RESHIPMENT = 'reshipment';
    // 退邮费
    // .. 更多


    public static function labels() : array
    {
        return [
            self::REFUND->value              => '退款',
            self::RETURN_GOODS_REFUND->value => '退货退款',
            self::EXCHANGE->value            => '换货',
            self::SERVICE->value             => '维修',
            self::RESHIPMENT->value          => '补寄',
        ];

    }
}
