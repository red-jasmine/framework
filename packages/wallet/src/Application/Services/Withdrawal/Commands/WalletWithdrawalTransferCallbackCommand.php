<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Wallet\Domain\Data\Payment\PaymentTransferData;


class WalletWithdrawalTransferCallbackCommand extends PaymentTransferData
{

    protected string $primaryKey = 'withdrawal_no';


}