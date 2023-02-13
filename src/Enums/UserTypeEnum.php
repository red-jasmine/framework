<?php

namespace RedJasmine\Support\Enums;

/**
 * 用户类型
 */
enum UserTypeEnum: string
{
    case USER = 'user'; // 用户

    case ADMIN = 'admin'; // 管理员

    case SELLER = 'seller'; // 卖家

    case SYSTEM = 'system'; // 系统

    case  GUEST = 'guest'; // 游客

    case SUPPLIER = 'supplier'; // 供应商


}
