<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Foundation\Data\Data;

class WalletWithdrawalTransferPrepareCommand extends Data
{
    protected string $primaryKey = 'withdrawal_no';
}