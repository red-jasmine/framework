<?php

namespace RedJasmine\Wallet\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

class WalletWithdrawalData extends Data
{
    /**
     * @var Money
     */
    public Money $amount;

    /**
     * 收款人
     * @var Payee
     */
    public Payee $payee;

}