<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 发货状态
 */
enum ShippingStatusEnum: string
{

    use EnumsHelper;

    case PENDING = 'pending'; // 预备发货
    case WAITING = 'waiting'; // 等待发货
    case PARTIAL = 'partial'; // 部分发货
    case SHIPPED = 'shipped'; // 全部已发货


    public static function labels() : array
    {
        return [
            self::PENDING->value => '待处理', // 发货管控中
            self::WAITING->value => '待发货',
            self::PARTIAL->value => '部分发货',
            self::SHIPPED->value => '已发货',
        ];
    }
}
