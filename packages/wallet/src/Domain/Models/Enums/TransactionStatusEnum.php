<?php

namespace RedJasmine\Wallet\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TransactionStatusEnum: string
{
    use EnumsHelper;

    case SUCCESS = 'success';

    public static function labels() : array
    {
        return [
            self::SUCCESS->value => '成功',
        ];
    }


}
