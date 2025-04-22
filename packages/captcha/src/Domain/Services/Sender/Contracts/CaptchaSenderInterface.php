<?php

namespace RedJasmine\Captcha\Domain\Services\Sender\Contracts;

use RedJasmine\Captcha\Domain\Models\Captcha;

interface CaptchaSenderInterface
{

    public function send(Captcha $captcha);

}