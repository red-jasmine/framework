<?php

namespace RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalReadRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;

class WalletWithdrawalReadRepository extends QueryBuilderReadRepository implements WalletWithdrawalReadRepositoryInterface
{


    public static string $modelClass = WalletWithdrawal::class;


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('wallet_id'),
        ];

    }

}