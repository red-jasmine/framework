<?php

namespace RedJasmine\Support\Enums;

/**
 * 用户类型
 */
enum UserType: string
{
    case USER = 'user'; // 用户

    case ADMIN = 'admin'; // 管理员

    case SELLER = 'seller'; // 卖家

    case SYSTEM = 'system'; // 系统

    case  GUEST = 'guest'; // 游客

    case SUPPLIER = 'supplier'; // 供应商


    /**
     * @return array
     */
    public static function options() : array
    {
        return [
            self::USER->value     => '用户',
            self::ADMIN->value    => '管理员',
            self::SELLER->value   => '卖家',
            self::SYSTEM->value   => '系统',
            self::GUEST->value    => '游客',
            self::SUPPLIER->value => '供应商',
        ];

    }
}
