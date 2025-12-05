<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Domain\Data\ApprovalData;

class WalletWithdrawalApprovalCommand extends ApprovalData
{


    protected string $primaryKey = 'withdrawal_no';


}