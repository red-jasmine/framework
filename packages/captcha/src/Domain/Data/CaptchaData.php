<?php

namespace RedJasmine\Captcha\Domain\Data;

use RedJasmine\Support\Foundation\Data\Data;

class CaptchaData extends Data
{
    public string $app;

    public string $type;

    public string $notifiableType;

    public string $notifiableId;


    public string $method;

    public int $expMinutes = 10;

}