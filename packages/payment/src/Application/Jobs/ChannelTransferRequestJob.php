<?php

namespace RedJasmine\Payment\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelRefundCreateCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelTransferCreateCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelHandlerService;

class ChannelTransferRequestJob implements ShouldQueue
{

    // TODO 唯一JOB
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(private readonly string $transferNo)
    {
    }

    public function handle() : void
    {
        // TODO 调用渠道处理服务 调用 转账方法
        try {
            $command = ChannelTransferCreateCommand::from(['transferNo' => $this->transferNo]);
            app(PaymentChannelHandlerService::class)->transfer($command);
        } catch (Throwable $throwable) {
            throw $throwable;
        }

    }


}
