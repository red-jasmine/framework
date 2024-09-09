<?php

namespace RedJasmine\Address\Domain\Models\Enums;

enum AddressValidateLevel: int
{

    case COUNTRY = 0; // 国家
    case PROVINCE = 1; // 省份
    case CITY = 2;// 城市
    case DISTRICT = 3;  // 区、县
    case STREET = 4; // 乡镇街道

}
