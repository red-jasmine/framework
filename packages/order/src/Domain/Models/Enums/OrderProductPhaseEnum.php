<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 子商品单 阶段
 */
enum OrderProductPhaseEnum: string
{
    use EnumsHelper;

    case PAID = 'paid'; // 定金支付、尾款支付

    case SHIPPING = 'shipping'; // 发货中

    case SHIPPED = 'shipped'; // 发货后

    case  SIGNED = 'signed'; // 签收后

    case CONFIRMED = 'confirmed'; // 确认收货后


    public static function lables() : array
    {
        return [
            self::PAID->value      => '支付后',
            self::SHIPPING->value  => '部分发货后',
            self::SHIPPED->value   => '已发货后',
            self::SIGNED->value    => '已签收后',
            self::CONFIRMED->value => '已确认后',
        ];
    }


}
