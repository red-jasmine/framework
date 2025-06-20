<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PromoterApplyTypeEnum: string
{
    use EnumsHelper;

    case REGISTER = 'register';
    case UPGRADE = 'upgrade';
    case Downgrade = 'downgrade';


    public static function labels() : array
    {
        return [
            self::REGISTER->value  => '注册',
            self::UPGRADE->value   => '升级',
            self::Downgrade->value => '降级',
        ];
    }
}
