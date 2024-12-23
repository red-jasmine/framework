<?php

namespace RedJasmine\Payment\Application\Listeners;


use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Queue\InteractsWithQueue;
use RedJasmine\Payment\Application\Jobs\ChannelRefundJob;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;

class RefundChannelListener implements ShouldQueueAfterCommit
{

    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle($event) : void
    {

        if ($event instanceof RefundCreatedEvent) {
            // 调度任务
            ChannelRefundJob::dispatch($event->refund->refund_no);
        }

    }
}
