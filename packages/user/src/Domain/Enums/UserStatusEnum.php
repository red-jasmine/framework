<?php

namespace RedJasmine\User\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 用户状态
 */
enum UserStatusEnum: string
{
    use EnumsHelper;


    case UNACTIVATED = 'unactivated';
    case ACTIVATED = 'activated';
    case SUSPENDED = 'suspended';
    case DISABLED = 'disabled';
    case CANCELED = 'canceled';


    public static function labels() : array
    {
        return [
            self::UNACTIVATED->value => __('red-jasmine-user::user.enums.status.unactivated'),
            self::ACTIVATED->value   => __('red-jasmine-user::user.enums.status.activated'),
            self::SUSPENDED->value   => __('red-jasmine-user::user.enums.status.suspended'),
            self::DISABLED->value    => __('red-jasmine-user::user.enums.status.disabled'),
            self::CANCELED->value    => __('red-jasmine-user::user.enums.status.canceled'),
        ];
    }

}
