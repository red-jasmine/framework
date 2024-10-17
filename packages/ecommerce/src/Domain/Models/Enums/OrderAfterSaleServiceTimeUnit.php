<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum OrderAfterSaleServiceTimeUnit: string
{

    use EnumsHelper;


    case MINUTE = 'minute';
    case HOUR = 'hour';
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
    case YEAR = 'year';

    public static function labels() : array
    {
        return [
            self::MINUTE->value => '分钟',
            self::HOUR->value   => '小时',
            self::DAY->value    => '天',
            self::WEEK->value   => '周',
            self::MONTH->value  => '月',
            self::YEAR->value   => '年',
        ];
    }


}
