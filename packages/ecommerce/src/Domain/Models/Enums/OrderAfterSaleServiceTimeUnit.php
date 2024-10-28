<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum OrderAfterSaleServiceTimeUnit: string
{

    use EnumsHelper;


    case Minute = 'minute';
    case Hour = 'hour';
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';

    public static function labels() : array
    {
        return [
            self::Minute->value => '分钟',
            self::Hour->value   => '小时',
            self::Day->value    => '天',
            self::Week->value   => '周',
            self::Month->value  => '月',
            self::Year->value   => '年',
        ];
    }


}
