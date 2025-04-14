<?php

namespace RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionReadRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;

class WalletTransactionReadRepository extends QueryBuilderReadRepository implements WalletTransactionReadRepositoryInterface
{

    public static string $modelClass = WalletTransaction::class;


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('wallet_id'),
        ];

    }

}