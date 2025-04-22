<?php

namespace RedJasmine\Captcha\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 验证码状态
 */
enum CaptchaStatusEnum: string
{
    use EnumsHelper;

    case  WAIT = 'wait';
    case  USED = 'used';


    public static function labels() : array
    {
        return [
            self::WAIT->value => '未使用',
            self::USED->value => '已验证',
        ];
    }

}
