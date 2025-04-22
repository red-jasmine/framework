<?php

namespace RedJasmine\Captcha\Domain\Services;

use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Captcha\Domain\Services\Sender\CaptchaSenderProviderManager;
use RedJasmine\Captcha\Exceptions\CaptchaException;

class CaptchaSenderService
{

    public function send(Captcha $captcha) : void
    {
        if (!$captcha->isAllowSend()) {
            throw new CaptchaException('不支持发送');
        }
        $captcha->send_status = CaptchaSendStatusEnum::SENDING;
        $config               = [];
        $senderProvider       = (new CaptchaSenderProviderManager($config))->create($captcha->notifiable_type);

        $senderProvider->send($captcha);


        $captcha->send_status = CaptchaSendStatusEnum::SEND;

    }

}