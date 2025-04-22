<?php

namespace RedJasmine\Captcha\Domain\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Captcha\Domain\Models\Captcha;

class CaptchaCreatedEvent implements ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(
        public Captcha $captcha

    ) {
    }
}