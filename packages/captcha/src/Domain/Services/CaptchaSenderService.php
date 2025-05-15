<?php

namespace RedJasmine\Captcha\Domain\Services;

use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Services\Sender\CaptchaSenderProviderManager;
use RedJasmine\Captcha\Domain\Services\Sender\Contracts\CaptchaSenderResult;
use RedJasmine\Captcha\Exceptions\CaptchaException;

class CaptchaSenderService
{

    /**
     * @param  Captcha  $captcha
     *
     * @return CaptchaSenderResult
     * @throws CaptchaException
     */
    public function send(Captcha $captcha) : Sender\Contracts\CaptchaSenderResult
    {
        if (!$captcha->isAllowSend()) {
            throw new CaptchaException('不支持发送');
        }
        $config         = [];
        $senderProvider = (new CaptchaSenderProviderManager($config))->create($captcha->method);

        $sendResult = $senderProvider->send($captcha);

        $captcha->setSendResult($sendResult);


        return $sendResult;

    }

}