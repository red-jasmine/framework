<?php

namespace RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum FreightTemplateStrategyTypeEnum: string
{

    use EnumsHelper;


    // 收费
    // 免费
    // 不可达
    case CHARGE = 'charge';
    case FREE = 'free';
    case UNREACHABLE = 'unreachable';


    public static function labels() : array
    {
        return [
            self::CHARGE->value      => trans('red-jasmine-logistics::freight-template.enums.region_type.charge'),
            self::FREE->value        => trans('red-jasmine-logistics::freight-template.enums.region_type.free'),
            self::UNREACHABLE->value => trans('red-jasmine-logistics::freight-template.enums.region_type.unreachable'),
        ];
    }
}
