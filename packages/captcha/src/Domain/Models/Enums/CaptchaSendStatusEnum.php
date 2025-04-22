<?php

namespace RedJasmine\Captcha\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 验证码发送状态
 */
enum CaptchaSendStatusEnum: string
{
    use EnumsHelper;

    case WAIT = 'wait';
    case  SENDING = 'sending';
    case  SEND = 'send';
    case  FAIL = 'fail';


    public static function labels() : array
    {
        return [
            self::WAIT->value    => '等待发送',
            self::SENDING->value => '发送中',
            self::SEND->value    => '发送成功',
            self::FAIL->value    => '发送失败',
        ];
    }


}
