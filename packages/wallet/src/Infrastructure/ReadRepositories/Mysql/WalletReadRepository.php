<?php

namespace RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletReadRepositoryInterface;

class WalletReadRepository extends QueryBuilderReadRepository implements WalletReadRepositoryInterface
{

    public $modelClass = Wallet::class;
}