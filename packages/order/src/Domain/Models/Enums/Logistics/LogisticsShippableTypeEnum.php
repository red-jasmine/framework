<?php

namespace RedJasmine\Order\Domain\Models\Enums\Logistics;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum LogisticsShippableTypeEnum: string
{
    use EnumsHelper;


    case  REFUND = 'refund';
    case  ORDER = 'order';

    public static function labels() : array
    {
        return [
            self::REFUND->value => '售后',
            self::ORDER->value  => '退款',
        ];
    }

}
