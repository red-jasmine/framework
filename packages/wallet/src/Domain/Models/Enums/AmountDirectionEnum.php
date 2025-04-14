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
            self::INCOME->value   => __('red-jasmine-wallet::wallet.enums.amount_direction.income'),
            self::EXPENSE->value  => __('red-jasmine-wallet::wallet.enums.amount_direction.expense'),
            self::FROZEN->value   => __('red-jasmine-wallet::wallet.enums.amount_direction.frozen'),
            self::UNFROZEN->value => __('red-jasmine-wallet::wallet.enums.amount_direction.unfrozen'),

        ];

    }
}
