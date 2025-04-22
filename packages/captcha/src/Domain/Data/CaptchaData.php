<?php

namespace RedJasmine\Captcha\Domain\Data;

use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class CaptchaData extends Data
{
    public string $app;

    public string $type;

    public string $notifiableType;

    public string $notifiableId;

    public int $expMinutes = 10;

}