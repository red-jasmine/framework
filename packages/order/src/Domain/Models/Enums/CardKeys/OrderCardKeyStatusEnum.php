<?php

namespace RedJasmine\Order\Domain\Models\Enums\CardKeys;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 卡密状态 TODO
 */
enum OrderCardKeyStatusEnum: string
{
    use EnumsHelper;

    case   SHIPPED = 'shipped';

    public static function labels() : array
    {
        return [
            self::SHIPPED->value => '已发货'
        ];
    }
}
