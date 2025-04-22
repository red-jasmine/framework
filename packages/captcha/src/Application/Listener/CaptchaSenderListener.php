<?php

namespace RedJasmine\Captcha\Application\Listener;

use RedJasmine\Captcha\Application\Jobs\CaptchaSendJob;
use RedJasmine\Captcha\Domain\Events\CaptchaCreatedEvent;


class CaptchaSenderListener
{
    public function __construct()
    {
    }

    public function handle(CaptchaCreatedEvent $event) : void
    {
        CaptchaSendJob::dispatch($event->captcha->id);
    }
}
