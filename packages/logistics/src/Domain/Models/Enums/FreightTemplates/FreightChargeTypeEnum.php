<?php

namespace RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 计费类型
 */
enum FreightChargeTypeEnum: string
{
    use EnumsHelper;

    case QUANTITY = 'quantity';
    case WEIGHT = 'weight';
    case VOLUME = 'volume';


    public static function labels() : array
    {

        return [
            self::WEIGHT->value   => '重量',
            self::QUANTITY->value => '数量',
            self::VOLUME->value   => '体积',
        ];
    }

}
