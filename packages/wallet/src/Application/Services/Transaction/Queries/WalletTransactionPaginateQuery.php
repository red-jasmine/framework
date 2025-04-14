<?php

namespace RedJasmine\Wallet\Application\Services\Transaction\Queries;


use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class WalletTransactionPaginateQuery extends PaginateQuery
{
    public int $walletId;
}