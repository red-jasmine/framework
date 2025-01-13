<?php

namespace RedJasmine\Payment\Application\Listeners;

use RedJasmine\Payment\Application\Jobs\ChannelRefundQueryJob;
use RedJasmine\Payment\Application\Jobs\ChannelRefundRequestJob;
use RedJasmine\Payment\Application\Jobs\ChannelTransferRequestJob;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundProcessingEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferExecutingEvent;

class PaymentChannelListener
{

    public function handle($event) : void
    {

        $this->transferHandler($event);
    }

    protected function transferHandler($event) : void
    {
        if ($event instanceof TransferExecutingEvent) {
            // 调度 渠道退款请求
            ChannelTransferRequestJob::dispatch($event->transfer->transfer_no);
        }


        if ($event instanceof RefundCreatedEvent) {
            // 调度任务
            ChannelRefundRequestJob::dispatch($event->refund->refund_no);
        }
        if ($event instanceof RefundProcessingEvent) {
            ChannelRefundQueryJob::dispatch($event->refund->refund_no)->delay(
                now()->addSeconds(config('red-jasmine.payment.refund_query_interval', 60))
            );
        }

    }

}
