<?php

namespace RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionReadRepositoryInterface;

class WalletTransactionReadRepository extends QueryBuilderReadRepository implements WalletTransactionReadRepositoryInterface
{

    public static string $modelClass = WalletTransaction::class;


}