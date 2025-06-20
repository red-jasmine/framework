<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PromoterApplyMethodEnum: string
{
    use EnumsHelper;

    case DISABLE = 'disable';
    case AUTO = 'auto';
    case MANUAL = 'manual'; // 方式

    public static function labels() : array
    {
        return [
            self::DISABLE->value => '关闭申请',
            self::AUTO->value    => '自动申请',
            self::MANUAL->value  => '手动申请',
        ];
    }
}
