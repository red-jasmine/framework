<?php

namespace RedJasmine\Wallet\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;

class WalletWithdrawalData extends Data
{


    /**
     * @var Amount
     */
    public Amount $amount;

}