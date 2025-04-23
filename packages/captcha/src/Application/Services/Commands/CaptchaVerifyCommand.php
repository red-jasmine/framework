<?php

namespace RedJasmine\Captcha\Application\Services\Commands;

use RedJasmine\Captcha\Domain\Data\CaptchaData;

class CaptchaVerifyCommand extends CaptchaData
{

    public string $code;

}