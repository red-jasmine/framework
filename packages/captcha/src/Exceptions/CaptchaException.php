<?php

namespace RedJasmine\Captcha\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class CaptchaException extends AbstractException
{

    public const SEND_ERROR        = '411011'; // 发送状态错
    public const SEND_STATUS_ERROR = '411012'; // 发送状态错
    public const CODE_ERROR        = '411013'; // 验证码错误
    public const SEND_FAIL         = '411014'; // 推送失败
}
