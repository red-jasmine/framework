<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class WalletRechargePaginateQuery extends PaginateQuery
{

    /**
     * 钱包类型
     * @var string
     */
    public string $walletType;

}