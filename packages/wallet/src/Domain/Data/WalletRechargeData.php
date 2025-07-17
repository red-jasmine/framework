<?php

namespace RedJasmine\Wallet\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

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
    public string $currency;

}