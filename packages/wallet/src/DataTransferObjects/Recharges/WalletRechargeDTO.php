<?php

namespace RedJasmine\Wallet\DataTransferObjects\Recharges;

use RedJasmine\Support\Data\Data;

class WalletRechargeDTO extends Data
{

    /**
     * 金额
     * @var string|int|float
     */
    public string|int|float $amount;


}
