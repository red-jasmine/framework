<?php

namespace RedJasmine\Wallet\Domain\Data\Payment;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

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