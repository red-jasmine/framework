<?php

namespace RedJasmine\Payment\Application\Listeners;

use RedJasmine\Payment\Application\Services\NotifyCommandService;
use RedJasmine\Payment\Domain\Contracts\AsyncNotifyInterface;

class NotifyListener
{
    public function __construct(
        public NotifyCommandService $service
    ) {
    }

    public function handle($event) : void
    {
        if (($event instanceof AsyncNotifyInterface) && $notifyCommand = $event->getAsyncNotify()) {
            $this->service->create($notifyCommand);
        }


    }
}
