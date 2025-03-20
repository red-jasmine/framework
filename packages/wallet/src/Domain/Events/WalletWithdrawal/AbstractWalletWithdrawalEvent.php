<?php

namespace RedJasmine\Wallet\Domain\Events\WalletWithdrawal;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;

abstract class AbstractWalletWithdrawalEvent implements ShouldDispatchAfterCommit
{

    use Dispatchable;


    public function __construct(public WalletWithdrawal $walletWithdrawal)
    {
    }
}