<?php

namespace RedJasmine\Wallet\Application\Services\Wallet\Commands;

use RedJasmine\Wallet\Domain\Data\WalletTransactionData;

/**
 * 交易创建流程
 */
class WalletTransactionCommand extends WalletTransactionData
{

    public int $id;

}