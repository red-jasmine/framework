<?php

namespace RedJasmine\Wallet\Application\Listeners;

use RedJasmine\Wallet\Application\Jobs\WalletWithdrawalTransferCreateJob;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalTransferPrepareEvent;

class WalletWithdrawalTransferListener
{
    public function __construct()
    {
    }

    public function handle(WalletWithdrawalTransferPrepareEvent $event) : void
    {
        // 触发 Job

        WalletWithdrawalTransferCreateJob::dispatch($event->walletWithdrawal->withdrawal_no);
    }
}
