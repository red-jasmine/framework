<?php

namespace RedJasmine\Card\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 卡密状态
 */
enum CardStatus: string
{
    use EnumsHelper;

    case UNSOLD = 'unsold'; // 未售

    case SOLD = 'sold'; // 已售


    case DISABLE = 'disable'; // 禁用


    public static function labels() : array
    {
        return [
            self::UNSOLD->value  => '未售',
            self::SOLD->value    => '已售',
            self::DISABLE->value => '禁用',
        ];
    }
}
