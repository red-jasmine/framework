<?php

namespace RedJasmine\Wallet;

use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Wallet\Actions\Recharges\RechargeCreateAction;
use RedJasmine\Wallet\Actions\Recharges\RechargePaidAction;
use RedJasmine\Wallet\DataTransferObjects\Recharges\RechargePaymentDTO;
use RedJasmine\Wallet\DataTransferObjects\Recharges\WalletRechargeDTO;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Services\WalletService;

/**
 * @see RechargeCreateAction::execute()
 * @method WalletRecharge create(int $walletId, WalletRechargeDTO $DTO)
 * @see RechargePaidAction::execute()
 * @method WalletRecharge paid(int $id, RechargePaymentDTO $DTO)
 */
class WalletRechargeService extends Service
{

    protected static ?string $actionsConfigKey = 'red-jasmine.wallet.actions.recharges';

    public function __construct(public WalletService $walletService)
    {
        parent::__construct();
    }


    public function find(int $id) : WalletRecharge
    {
        return WalletRecharge::findOrFail($id);
    }

    public function findLock(int $id) : WalletRecharge
    {
        return WalletRecharge::lockForUpdate()->findOrFail($id);
    }



}
