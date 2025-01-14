<?php

namespace RedJasmine\Payment\Application\Listeners;

use RedJasmine\Payment\Application\Jobs\ChannelRefundQueryJob;
use RedJasmine\Payment\Application\Jobs\ChannelRefundCreateJob;
use RedJasmine\Payment\Application\Jobs\ChannelTransferQueryJob;
use RedJasmine\Payment\Application\Jobs\ChannelTransferCreateJob;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundExecutingEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundProcessingEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferAbnormalEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferExecutingEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferProcessingEvent;

class PaymentChannelListener
{

    public function handle($event) : void
    {

        $this->refundHandler($event);
        $this->transferHandler($event);
    }

    protected function refundHandler($event) : void
    {

        if ($event instanceof RefundExecutingEvent) {
            // 调度任务
            ChannelRefundCreateJob::dispatch($event->refund->refund_no);
        }
        if ($event instanceof RefundProcessingEvent) {
            ChannelRefundQueryJob::dispatch($event->refund->refund_no)->delay(
                now()->addSeconds(config('red-jasmine.payment.refund_query_interval', 60))
            );
        }
    }

    protected function transferHandler($event) : void
    {
        if ($event instanceof TransferExecutingEvent) {
            // 调度 渠道退款请求
            ChannelTransferCreateJob::dispatch($event->transfer->transfer_no);
        }
        // 如果异常 那么需要二次查询确认
        if ($event instanceof TransferProcessingEvent || $event instanceof TransferAbnormalEvent) {
            ChannelTransferQueryJob::dispatch($event->transfer->transfer_no)->delay(
                now()->addSeconds(config('red-jasmine.payment.transfer_query_interval', 60))
            );
        }


    }

}
