<?php

namespace RedJasmine\Region\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RegionLevel: int
{
    use EnumsHelper;

    case  COUNTRY = 0; // 国家
    case  PROVINCE = 1; // 省
    case  CITY = 2; // 市
    case  DISTRICT = 3; // 区、县
    case  STREET = 4; // 乡镇街道
    case  VILLAGE = 5; // 村庄


    public static function labels() : array
    {
        return [
            self::COUNTRY->value  => '国家',
            self::PROVINCE->value => '省',
            self::CITY->value     => '市',
            self::DISTRICT->value => '县、区、市',
            self::STREET->value   => '乡、镇、街道',
            self::VILLAGE->value  => '村、社区',
        ];
    }
}
