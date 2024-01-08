<?php

namespace RedJasmine\Card\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 卡密状态
 */
enum CardStatus: int
{
    use EnumsHelper;

    case ENABLE = 1; // 启用
    case DISABLE = 0; // 禁用


    public static function labels() : array
    {
        return [
            self::ENABLE->value => '启用',
            self::ENABLE->value => '禁用',
        ];
    }
}
