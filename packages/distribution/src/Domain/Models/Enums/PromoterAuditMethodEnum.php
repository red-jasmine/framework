<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PromoterAuditMethodEnum: string
{
    use EnumsHelper;

    case DISABLE = 'disable';
    case AUTO = 'auto';
    case MANUAL = 'manual';

    public static function labels() : array
    {
        return [
            self::DISABLE->value => '关闭',
            self::AUTO->value    => '自动',
            self::MANUAL->value  => '手动',
        ];
    }
}
