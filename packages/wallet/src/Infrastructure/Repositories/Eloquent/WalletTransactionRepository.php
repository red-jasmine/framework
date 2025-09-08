<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;


class WalletTransactionRepository extends Repository implements WalletTransactionRepositoryInterface
{


    protected static string $modelClass = WalletTransaction::class;


}