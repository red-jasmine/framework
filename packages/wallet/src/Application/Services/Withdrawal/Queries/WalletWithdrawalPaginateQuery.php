<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class WalletWithdrawalPaginateQuery extends PaginateQuery
{
    public string $walletType;
}