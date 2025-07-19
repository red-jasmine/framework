<?php

namespace RedJasmine\Wallet\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalTransferPrepareCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use Throwable;

class WalletWithdrawalTransferCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $withdrawalNo
    ) {

    }

    public function handle() : void
    {
        try {
            $command = new WalletWithdrawalTransferPrepareCommand();
            $command->setKey($this->withdrawalNo);

            app(WalletWithdrawalApplicationService::class)->transferPrepare($command);
        } catch (Throwable $throwable) {
            throw  $throwable;
            report($throwable);

        }

    }
}
