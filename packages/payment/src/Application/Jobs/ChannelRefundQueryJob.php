<?php

namespace RedJasmine\Payment\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelRefundQueryCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelApplicationService;
use Throwable;

class ChannelRefundQueryJob implements ShouldQueue
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
        // 异步查询退款
        try {
            $command = ChannelRefundQueryCommand::from(['refundNo' => $this->refundNo]);
            // TODO 如果查询失败 需要 重试
            app(PaymentChannelApplicationService::class)->refundQuery($command);

        } catch (Throwable $throwable) {
            throw $throwable;
        }

    }
}
