<?php

namespace RedJasmine\Wallet\Application\Services\Transaction;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;

class WalletTransactionApplicationService extends ApplicationService
{

    public function __construct(
        public WalletTransactionRepositoryInterface $repository,
        public WalletTransactionReadRepositoryInterface $readRepository

    ) {
    }

    protected static string $modelClass = WalletTransaction::class;
}