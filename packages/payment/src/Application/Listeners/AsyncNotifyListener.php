<?php

namespace RedJasmine\Payment\Application\Listeners;

use RedJasmine\Payment\Application\Services\AsyncNotify\AsyncNotifyCommandService;
use RedJasmine\Payment\Domain\Contracts\AsyncNotifyInterface;

class AsyncNotifyListener
{
    public function __construct(
        public AsyncNotifyCommandService $service
    )
    {
    }

    public function handle($event) : void
    {

        if (($event instanceof AsyncNotifyInterface) && $notifyCommand = $event->getAsyncNotify()) {
            $this->service->create($notifyCommand);
        }


    }
}
