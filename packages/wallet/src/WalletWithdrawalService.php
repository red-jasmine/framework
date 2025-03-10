<?php

namespace RedJasmine\Wallet;

use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Wallet\Actions\Withdrawals\WithdrawalCreateAction;
use RedJasmine\Wallet\DataTransferObjects\Withdrawals\WalletWithdrawalDTO;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;

/**
 * @see WithdrawalCreateAction::execute()
 * @method WalletWithdrawal create(int $walletId, WalletWithdrawalDTO $DTO)
 */
class WalletWithdrawalService extends Service
{
    protected static ?string $actionsConfigKey = 'red-jasmine.wallet.actions.withdrawals';

    public function __construct(public WalletService $walletService)
    {
        parent::__construct();
    }




}
