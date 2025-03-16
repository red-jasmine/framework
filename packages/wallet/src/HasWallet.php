<?php

namespace RedJasmine\Wallet;

use Exception;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Services\WalletService;

trait HasWallet
{
    /**
     * @param string $wallet
     *
     * @return Wallet
     * @throws Exception
     */
    public function wallet(string $wallet) : Wallet
    {
        return app(WalletService::class)->wallet($this, $wallet);
    }

    public function wallets()
    {
        return app(WalletService::class)->walletsByOwner($this);
    }

}
