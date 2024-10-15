<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 支付类型
 */
enum PayTypeEnum: string
{
    use EnumsHelper;


    case ONLINE = 'online';
    case FREE = 'free';
    case OFFLINE = 'offline';
    case COMPANY = 'company';
    case BANK = 'bank';


    public static function labels() : array
    {
        return [
            self::ONLINE->value  => '在线',
            self::FREE->value    => '免付',
            self::OFFLINE->value => '线下',
            self::COMPANY->value => '对公',
            self::BANK->value    => '转账',
        ];

    }

}
