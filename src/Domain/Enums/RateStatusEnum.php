<?php

namespace RedJasmine\Order\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RateStatusEnum: string
{
    use EnumsHelper;

    case NIL = 'nil';
    case WAIT_RATE = 'wait_rate';
    case RATED = 'rated';

    public static function labels() : array
    {
        return [
            self::NIL->value         => '',
            self::WAIT_RATE->value => '待评价',
            self::RATED->value     => '已评价'

        ];
    }
}
