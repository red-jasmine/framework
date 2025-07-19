<?php

namespace RedJasmine\Wallet\Domain\Data\Payment;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;
use RedJasmine\Wallet\Domain\Models\ValueObjects\Payee;

class WalletTransferData extends Data
{
    /**
     * 业务单号
     * @var string
     */
    public string $businessNo;

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