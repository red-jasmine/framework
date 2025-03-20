<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Wallet\Domain\Data\WalletWithdrawalPaymentData;


class WalletWithdrawalPaymentCommand extends WalletWithdrawalPaymentData
{

    public string $withdrawalNo;


}