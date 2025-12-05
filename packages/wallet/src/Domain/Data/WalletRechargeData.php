<?php

namespace RedJasmine\Wallet\Domain\Data;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 钱包充值
 */
class WalletRechargeData extends Data
{


    /**
     * 充值的钱包金额
     * @var Money
     */
    public Money $amount;

    /**
     * 支付货币
     * @var string
     */
    public string $paymentCurrency = 'CNY';

}