<?php

namespace RedJasmine\Wallet\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AmountDirectionEnum: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';
    case FROZEN = 'frozen'; // 冻结
    case UNFROZEN = 'unfrozen'; // 解冻

    use EnumsHelper;

    public static function labels() : array
    {
        return [
            self::INCOME->value   => '收入',
            self::EXPENSE->value  => '支出',
            self::FROZEN->value   => '冻结',
            self::UNFROZEN->value => '解冻',

        ];

    }
}
