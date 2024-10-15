<?php

namespace RedJasmine\Wallet\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum WalletStatusEnum: int
{
    use EnumsHelper;

    case ENABLE = 1;
    case DISABLE = 0;

    public static function labels() : array
    {

        return [
            self::ENABLE->value => '启用',
            self::ENABLE->value => '启用',
        ];
    }
}
