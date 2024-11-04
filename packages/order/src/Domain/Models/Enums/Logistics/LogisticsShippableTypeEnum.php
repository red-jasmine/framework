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
            self::REFUND->value => __('red-jasmine-order::logistics.enums.shippable_type.refund'),
            self::ORDER->value  => __('red-jasmine-order::logistics.enums.shippable_type.order'),
        ];
    }

}
