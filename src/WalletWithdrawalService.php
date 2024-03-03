<?php

namespace RedJasmine\Wallet;

use RedJasmine\Support\Foundation\Service\Service;

class WalletWithdrawalService extends Service
{
    protected static ?string $actionsConfigKey = 'red-jasmine.wallet.actions.withdrawals';

    public function __construct(public WalletService $walletService)
    {
    }


}
