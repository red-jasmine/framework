<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RateStatusEnum: string
{
    use EnumsHelper;


    case PENDING = 'pending';
    case RATED = 'rated';

    public static function labels() : array
    {
        return [

            self::PENDING->value => '待评价',
            self::RATED->value   => '已评价'

        ];
    }
}
