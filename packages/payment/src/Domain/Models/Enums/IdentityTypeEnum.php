<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum IdentityTypeEnum: string
{
    use EnumsHelper;

    case  USER_ID = 'USER_ID';
    case  LOGIN_ID = 'LOGIN_ID';
    case  OPEN_ID = 'OPEN_ID';


    public static function labels() : array
    {
        return [
            self::USER_ID->value  => '用户ID',
            self::LOGIN_ID->value => '登录ID',
            self::OPEN_ID->value  => 'openID',
        ];
    }

}
