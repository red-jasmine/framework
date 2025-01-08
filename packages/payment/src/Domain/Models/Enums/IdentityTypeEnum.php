<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum IdentityTypeEnum: string
{
    use EnumsHelper;

    case  USER_ID = 'user_id';
    case  LOGIN_ID = 'login_id';
    case  OPEN_ID = 'open_id';


    public static function labels() : array
    {
        return [
            self::USER_ID->value  => '用户ID',
            self::LOGIN_ID->value => '登录ID',
            self::OPEN_ID->value  => 'openID',
        ];
    }

}
