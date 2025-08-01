<?php

namespace RedJasmine\Region\Domain\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RegionTypeEnum: string
{

    use EnumsHelper;


    case  COUNTRY = 'country'; // 国家
    case  PROVINCE = 'province'; // 省
    case  CITY = 'city'; // 城市
    case  DISTRICT = 'district'; // 县市区
    case  STREET = 'street'; // 乡镇街道
    case  VILLAGE = 'village'; // 村庄

    public static function labels() : array
    {
        return [
            self::COUNTRY->value  => '国家',
            self::PROVINCE->value => '省',
            self::CITY->value     => '城市',
            self::DISTRICT->value => '县区',
            self::STREET->value   => '街道乡镇',
            self::VILLAGE->value  => '村庄社区',
        ];
    }
}
