<?php

namespace RedJasmine\Card\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 卡密状态
 */
enum CardStatus: string
{
    use EnumsHelper;

    case ENABLE = 'enable'; // 启用
    case DISABLE = 'disable'; // 禁用


    public static function labels() : array
    {
        return [
            self::ENABLE->value => '启用',
            self::ENABLE->value => '禁用',
        ];
    }
}
