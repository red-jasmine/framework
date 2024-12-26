<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum NotifyBusinessTypeEnum: string
{
    use EnumsHelper;

    case  TRADE = 'trade';
    case  REFUND = 'refund';


    public static function labels() : array
    {
        return [
            self::REFUND->value => '退款',
            self::TRADE->value  => '交易',
        ];
    }

}
