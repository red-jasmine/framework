<?php

namespace RedJasmine\Payment\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Payment\Application\Commands\Notify\NotifySendCommand;
use RedJasmine\Payment\Application\Services\AsyncNotifyCommandService;

class AsyncNotifySendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $notifyNo)
    {
    }

    public function handle() : void
    {
        // 异步发送通知
        app(AsyncNotifyCommandService::class)->send(new NotifySendCommand(notifyNo: $this->notifyNo));
    }
}
