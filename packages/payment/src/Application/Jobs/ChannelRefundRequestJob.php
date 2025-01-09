<?php

namespace RedJasmine\Payment\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelRefundCreateCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelHandlerService;
use Throwable;

class ChannelRefundRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(private readonly string $refundNo)
    {
    }


    /**
     * @return void
     * @throws Throwable
     */
    public function handle() : void
    {
        // 异步请求退款
        try {
            $command = ChannelRefundCreateCommand::from([ 'refundNo' => $this->refundNo ]);
            app(PaymentChannelHandlerService::class)->refund($command);

        } catch (Throwable $throwable) {
            throw $throwable;
        }

    }
}
