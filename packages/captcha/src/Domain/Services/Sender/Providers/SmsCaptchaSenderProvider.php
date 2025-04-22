<?php

namespace RedJasmine\Captcha\Domain\Services\Sender\Providers;

use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Services\Sender\Contracts\CaptchaSenderInterface;
use RedJasmine\Captcha\Exceptions\CaptchaException;

class SmsCaptchaSenderProvider implements CaptchaSenderInterface
{

    public const string  NAME = 'sms';

    public function send(Captcha $captcha)
    {
        try {
            return app('easy-sms')->send($captcha->notifiable_id, [
                'content'  => '您的验证码为：${code}，请勿泄露于他人！',
                'template' => 'SMS_276355183',
                'data'     => [
                    'code' => $captcha->code
                ],
            ]);
        } catch (Throwable $throwable) {
            // 发送
            report($throwable);
            throw new CaptchaException('推送失败', CaptchaException::SEND_FAIL);
        }
    }


}