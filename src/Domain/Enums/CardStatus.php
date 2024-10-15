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
    case SOLD = 'sold'; // 已售

    public static function labels() : array
    {
        return [
            self::ENABLE->value  => __('red-jasmine-card::card.enums.status.enable'),
            self::DISABLE->value => __('red-jasmine-card::card.enums.status.disable'),
            self::SOLD->value    => __('red-jasmine-card::card.enums.status.sold'),
        ];
    }
}
