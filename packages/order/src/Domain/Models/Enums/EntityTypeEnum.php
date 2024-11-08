<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum EntityTypeEnum: string
{
    use EnumsHelper;


    case  REFUND = 'refund';
    case  ORDER = 'order';

    public static function labels() : array
    {
        return [
            self::REFUND->value => __('red-jasmine-order::common.enums.entity_type.refund'),
            self::ORDER->value  => __('red-jasmine-order::common.enums.entity_type.order'),
        ];
    }

}
