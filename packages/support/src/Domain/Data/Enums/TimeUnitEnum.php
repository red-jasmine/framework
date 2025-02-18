<?php

namespace RedJasmine\Support\Domain\Data\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 时间单位
 */
enum TimeUnitEnum: string
{

    use EnumsHelper;

    case  SECOND = 'second';
    case  MINUTE = 'minute';
    case  HOUR = 'hour';
    case  DAY = 'day';
    case  MONTH = 'month';
    case  QUARTER = 'quarter';
    case  YEAR = 'year';
    case  FOREVER = 'forever';


    public static function labels() : array
    {
        return [
            self::DAY->value     => '天',
            self::HOUR->value    => '小时',
            self::MINUTE->value  => '分钟',
            self::SECOND->value  => '秒',
            self::MONTH->value   => '月',
            self::QUARTER->value => '季度',
            self::YEAR->value    => '年',
            self::FOREVER->value => '永久',
        ];
    }

}
