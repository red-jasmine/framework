<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 子商品单 阶段
 */
enum OrderProductPhaseEnum: string
{
    use EnumsHelper;

    case PAYMENT = 'payment'; // 定金支付、尾款支付

    case SHIPPING = 'shipping';

    case SHIPPED = 'shipped';

    case  SIGNED = 'signed';

    case CONFIRMED = 'confirmed';


    public static function lables() : array
    {
        return [
            self::PAYMENT->value   => '支付后',
            self::SHIPPING->value  => '部分发货后',
            self::SHIPPED->value   => '已发货后',
            self::SIGNED->value    => '已签收后',
            self::CONFIRMED->value => '已确认后',
        ];
    }


}
