<?php

namespace RedJasmine\Wallet\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum WalletStatuaEnum: int
{
    use EnumsHelper;

    case ENABLE = 1;
    case DISABLE = 0;
}
