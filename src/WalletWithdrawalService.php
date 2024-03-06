<?php

namespace RedJasmine\Wallet;

use Exception;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Wallet\Actions\Withdrawals\WithdrawalCreateAction;
use RedJasmine\Wallet\DataTransferObjects\Withdrawals\WalletWithdrawalDTO;
use RedJasmine\Wallet\Models\WalletWithdrawal;

/**
 * @see WithdrawalCreateAction::execute()
 * @method WalletWithdrawal create(int $walletId, WalletWithdrawalDTO $DTO)
 */
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
