<?php

namespace RedJasmine\UserCore\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 用户状态
 */
enum UserStatusEnum: string
{
    use EnumsHelper;


    case UNACTIVATED = 'unactivated'; // 未激活
    case ACTIVATED = 'activated'; // 激活
    case SUSPENDED = 'suspended'; // 停用
    case DISABLED = 'disabled'; // 禁用
    case CANCELED = 'canceled'; // 已注销


    public static function labels() : array
    {
        return [
            self::UNACTIVATED->value => __('red-jasmine-user-core::user.enums.status.unactivated'),
            self::ACTIVATED->value   => __('red-jasmine-user-core::user.enums.status.activated'),
            self::SUSPENDED->value   => __('red-jasmine-user-core::user.enums.status.suspended'),
            self::DISABLED->value    => __('red-jasmine-user-core::user.enums.status.disabled'),
            self::CANCELED->value    => __('red-jasmine-user-core::user.enums.status.canceled'),
        ];
    }

}
