<?php

namespace RedJasmine\Payment\Application\Listeners;

use RedJasmine\Payment\Application\Jobs\AsyncNotifySendJob;
use RedJasmine\Payment\Application\Services\AsyncNotify\AsyncNotifyCommandService;
use RedJasmine\Payment\Domain\Contracts\AsyncNotifyInterface;
use RedJasmine\Payment\Domain\Events\Notifies\NotifyCreateEvent;

class AsyncNotifyListener
{
    public function __construct(
        public AsyncNotifyCommandService $service
    )
    {
    }

    public function handle($event) : void
    {
        if ($event instanceof NotifyCreateEvent) {
            AsyncNotifySendJob::dispatch($event->notify->notify_no);
        }
        if (($event instanceof AsyncNotifyInterface) && $notifyCommand = $event->getAsyncNotify()) {
            $this->service->create($notifyCommand);
        }


    }
}
