<?php

namespace RedJasmine\Wallet\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum WalletStatusEnum: string
{
    use EnumsHelper;

    case ENABLE = 'enable';

    case DISABLE = 'disable';

    public static function labels() : array
    {

        return [
            self::ENABLE->value => '启用',
            self::DISABLE->value => '停用',
        ];
    }
}
