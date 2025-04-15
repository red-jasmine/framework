<?php

namespace RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeReadRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;

class WalletRechargeReadRepository extends QueryBuilderReadRepository implements WalletRechargeReadRepositoryInterface
{


    public static string $modelClass = WalletRecharge::class;


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('wallet_id'),
        ];

    }

}