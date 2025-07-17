<?php

namespace RedJasmine\Wallet\Application\Services\Transaction\Queries;




use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 *
 */
class UserWalletTransactionFindQuery extends FindQuery
{


    protected string $primaryKey = 'transaction_no';

}