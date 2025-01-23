<?php

namespace RedJasmine\User\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 用户状态
 */
enum UserStatusEnum: string
{
    use EnumsHelper;

    // 正常的
    // 未激活
    case UNACTIVATED = 'unactivated';
    case NORMAL = 'normal';
    case SUSPENDED = 'suspended';
    case DISABLED = 'disabled';
    case CANCELED = 'canceled';


    public static function labels() : array
    {
        return [
            self::UNACTIVATED->value => '待激活',
            self::NORMAL->value      => '正常',
            self::SUSPENDED->value   => '停用',
            self::DISABLED->value    => '禁用',
            self::CANCELED->value    => '已注销',

        ];
    }

}
