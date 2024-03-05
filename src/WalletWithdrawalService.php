<?php

namespace RedJasmine\Wallet;

use Exception;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;

class WalletWithdrawalService extends Service
{
    protected static ?string $actionsConfigKey = 'red-jasmine.wallet.actions.withdrawals';

    public function __construct(public WalletService $walletService)
    {
    }


    /**
     * @return int
     * @throws Exception
     */
    public function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }

}
