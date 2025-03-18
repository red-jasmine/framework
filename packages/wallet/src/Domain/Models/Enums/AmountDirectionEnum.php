<?php

namespace RedJasmine\Wallet\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AmountDirectionEnum: string
{
    case INCOME = 'income';

    case EXPENDITURE = 'expenditure';

    use EnumsHelper;

    public static function labels() : array
    {
        return [
            self::INCOME->value      => '收入',
            self::EXPENDITURE->value => '支出',
        ];

    }
}
