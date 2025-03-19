<?php

namespace RedJasmine\Wallet\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AmountDirectionEnum: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';
    case OTHER = 'other';

    use EnumsHelper;

    public static function labels() : array
    {
        return [
            self::INCOME->value  => '收入',
            self::EXPENSE->value => '支出',
            self::OTHER->value   => '其他',
        ];

    }
}
