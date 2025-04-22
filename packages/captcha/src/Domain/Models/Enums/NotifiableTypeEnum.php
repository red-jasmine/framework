<?php

namespace RedJasmine\Captcha\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 发送账号类型
 */
enum NotifiableTypeEnum: string
{
    use EnumsHelper;

    case MOBILE = 'mobile';
    case  EMAIL = 'email';

    // ... 微信公众号用户


    public static function labels() : array
    {
        return [
            self::MOBILE->value => '手机号',
            self::EMAIL->value  => '邮箱',
        ];
    }
}
