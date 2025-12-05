<?php

namespace RedJasmine\Wallet\Domain\Data\Payment;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;

class WalletTradeData extends Data
{


    /**
     * 业务单号
     * @var string
     */
    public string $businessNo;
    /**
     * 金额
     * @var Money
     */
    public Money $amount;

}