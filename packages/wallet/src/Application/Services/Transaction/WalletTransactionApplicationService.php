<?php

namespace RedJasmine\Wallet\Application\Services\Transaction;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;

/**
 * 钱包交易应用服务
 */
class WalletTransactionApplicationService extends ApplicationService
{

    public function __construct(
        public WalletTransactionRepositoryInterface $repository

    ) {
    }

    protected static string $modelClass = WalletTransaction::class;
}
