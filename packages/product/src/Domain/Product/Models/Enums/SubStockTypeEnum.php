<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum SubStockTypeEnum: string
{
    use EnumsHelper;

    case DEFAULT = 'default';

    case ORDER = 'order';

    case PAYMENT = 'payment';


    public static function labels() : array
    {
        return [
            self::DEFAULT->value => '默认',
            self::ORDER->value   => '下单',
            self::PAYMENT->value => '付款',
        ];

    }

}
