<?php

namespace RedJasmine\Wallet\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum WalletSystemAppEnum: string
{
    use EnumsHelper;


    case RECHARGE = 'recharge';

    case WITHDRAWAL = 'withdrawal';

    case TRANSFER = 'transfer';
}
