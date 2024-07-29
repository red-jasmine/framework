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
            self::FULL->value    => '全款',
            self::DEPOSIT->value => '预付',
            self::TAIL->value    => '尾款',
            self::REFUND->value  => '退款',
        ];
    }


}
