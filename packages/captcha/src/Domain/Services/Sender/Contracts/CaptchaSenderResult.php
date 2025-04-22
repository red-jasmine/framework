<?php

namespace RedJasmine\Captcha\Domain\Services\Sender\Contracts;

use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Support\Data\Data;

class CaptchaSenderResult extends Data
{

    public CaptchaSendStatusEnum $sendStatus;

    public ?string $channel;

    public ?string $channelNo;

    public ?string $channelMessage;

}