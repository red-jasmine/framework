<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;


class WalletTransactionRepository extends EloquentRepository implements WalletTransactionRepositoryInterface
{


    protected static string $eloquentModelClass = WalletTransaction::class;


}