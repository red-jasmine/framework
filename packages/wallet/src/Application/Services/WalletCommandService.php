<?php

namespace RedJasmine\Wallet\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repository\WalletRepositoryInterface;

class WalletCommandService extends ApplicationCommandService
{

    public function __construct(
        public WalletRepositoryInterface $repository
    ) {
    }

    protected static string $modelClass = Wallet::class;
}