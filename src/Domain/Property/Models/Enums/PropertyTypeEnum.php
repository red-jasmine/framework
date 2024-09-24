<?php

namespace RedJasmine\Product\Domain\Property\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PropertyTypeEnum: string
{
    use EnumsHelper;

    case  TEXT = 'text';

    case  SELECT = 'select';

    case  MULTIPLE = 'multiple';

    case  DATE = 'date';


    public static function labels() : array
    {
        return [
            self::TEXT->value     => '输入',
            self::SELECT->value   => '选择',
            self::DATE->value     => '时间',
        ];

    }
}
