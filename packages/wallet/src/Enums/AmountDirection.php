<?php

namespace RedJasmine\Wallet\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AmountDirection: int
{
    case INCOME = 1;

    case EXPENDITURE = 0;

    use EnumsHelper;

    public static function labels() : array
    {
        return [
            self::INCOME->value      => '收入',
            self::EXPENDITURE->value => '支出',
        ];

    }
}
