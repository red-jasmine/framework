<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Data\Data;

class WalletWithdrawalTransferPrepareCommand extends Data
{
    protected string $primaryKey = 'withdrawal_no';
}