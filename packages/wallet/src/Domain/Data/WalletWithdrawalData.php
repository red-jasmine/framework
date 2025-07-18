<?php

namespace RedJasmine\Wallet\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;
use RedJasmine\Wallet\Domain\Models\ValueObjects\Payee;

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


    /**
     * 需要转换的货币
     * @var string
     */
    public string $currency;


}