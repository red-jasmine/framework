<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AccountTypeEnum: string
{
    use EnumsHelper;

    case  USER_ID = 'USER_ID';
    case  LOGIN_ID = 'LOGIN_ID';
    case  OPEN_ID = 'OPEN_ID';
    case  BANK_CARD = 'BANK_CARD';// 银行卡
    case  COMPANY_ACCOUNT = 'COMPANY_ACCOUNT';// 对公账户


    public static function labels() : array
    {
        return [
            self::USER_ID->value         => '用户ID',
            self::LOGIN_ID->value        => '登录ID',
            self::OPEN_ID->value         => 'openID',
            self::BANK_CARD->value       => '银行卡',
            self::COMPANY_ACCOUNT->value => '对公账户',
        ];
    }

}
