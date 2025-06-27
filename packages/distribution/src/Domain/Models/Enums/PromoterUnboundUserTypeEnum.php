<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 解绑类型
 */
enum PromoterUnboundUserTypeEnum: string
{

    use EnumsHelper;

    case EXPIRED = 'expired';
    case COMPETE = 'compete';


    public static function labels() : array
    {
        return [
            self::EXPIRED->value => '过期',
            self::COMPETE->value => '抢客',
        ];

    }

    public static function colors() : array
    {
        return [
            self::EXPIRED->value => 'danger',
            self::COMPETE->value => 'gray',
        ];

    }

    public static function icons() : array
    {
        return [
            self::EXPIRED->value => 'heroicon-o-link-slash',
            self::COMPETE->value => 'heroicon-o-link',
        ];
    }
}



