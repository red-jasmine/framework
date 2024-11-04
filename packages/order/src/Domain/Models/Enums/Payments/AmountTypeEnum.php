<?php

namespace RedJasmine\Order\Domain\Models\Enums\Payments;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AmountTypeEnum: string
{

    use EnumsHelper;

    case FULL = 'full';
    case DEPOSIT = 'deposit';
    case TAIL = 'tail';
    case REFUND = 'refund';


    public static function labels() : array
    {

        return [
            self::FULL->value    => __('red-jasmine-order::payment.enums.amount_type.full'),
            self::DEPOSIT->value => __('red-jasmine-order::payment.enums.amount_type.deposit'),
            self::TAIL->value    => __('red-jasmine-order::payment.enums.amount_type.tail'),
            self::REFUND->value  => __('red-jasmine-order::payment.enums.amount_type.refund'),
        ];
    }


}
