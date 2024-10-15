<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 退款阶段
 */
enum RefundPhaseEnum: string
{
    use EnumsHelper;

    case  ON_SALE = 'on_sale';

    case  AFTER_SALE = 'after_sale';


    public static function labels() : array
    {
        return [
            self::ON_SALE->value    => '售中',
            self::AFTER_SALE->value => '售后',
        ];

    }
}
