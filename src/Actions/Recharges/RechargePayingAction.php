<?php

namespace RedJasmine\Wallet\Actions\Recharges;

use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Wallet\Models\WalletRecharge;
use RedJasmine\Wallet\WalletRechargeService;

class RechargePayingAction extends Action
{

    public WalletRechargeService $service;

    protected ?string $pipelinesConfigKey = 'red-jasmine.wallet.pipelines.recharges.paying';


    public function isAllow(WalletRecharge $walletRecharge) : bool
    {

        return true;
    }

    public function execute(int $id,)
    {

    }
}
