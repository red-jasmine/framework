<?php

namespace RedJasmine\Payment\Application\Listeners;


use RedJasmine\Payment\Application\Jobs\ChannelRefundQueryJob;
use RedJasmine\Payment\Application\Jobs\ChannelRefundRequestJob;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundProcessingEvent;

class RefundChannelListener
{


    /**
     * @param $event
     *
     * @return void
     */
    public function handle($event) : void
    {
        if ($event instanceof RefundCreatedEvent) {
            // 调度任务
            ChannelRefundRequestJob::dispatch($event->refund->refund_no);
        }
        if ($event instanceof RefundProcessingEvent) {


            ChannelRefundQueryJob::dispatch($event->refund->refund_no)
                                 ->delay(
                                     now()->addSeconds(config('red-jasmine.payment.refund_query_interval', 60))
                                 );

        }


    }
}
