<?php

namespace RedJasmine\Payment\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelTransferQueryCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\PaymentChannelApplicationService;

class ChannelTransferQueryJob implements ShouldQueue
{


    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(private readonly string $transferNo)
    {
    }

    public function handle() : void
    {

        // 执行失败需要再次执行 TODO
        try {
            $command = ChannelTransferQueryCommand::from([ 'transferNo' => $this->transferNo ]);
            app(PaymentChannelApplicationService::class)->transferQuery($command);
        } catch (Throwable $throwable) {
            throw $throwable;
        }

    }


}
