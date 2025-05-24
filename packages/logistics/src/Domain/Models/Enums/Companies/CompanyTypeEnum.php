<?php

namespace RedJasmine\Logistics\Domain\Models\Enums\Companies;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum CompanyTypeEnum: string
{
    use EnumsHelper;


    // 国内
    case  DOMESTIC = 'domestic';
    // 国际
    case INTERNATIONAL = 'international';


    public static function labels() : array
    {
        return [
            self::DOMESTIC->value      => trans('red-jasmine-logistics::logistics-companies.enums.type.domestic'),
            self::INTERNATIONAL->value => trans('red-jasmine-logistics::logistics-companies.enums.type.international'),
        ];
    }
}
