<?php

namespace RedJasmine\Wallet\Enums;

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
