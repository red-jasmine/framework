<?php

namespace RedJasmine\Product\Domain\Attribute\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductAttributeTypeEnum: string
{
    use EnumsHelper;

    case  TEXT = 'text';

    case  SELECT = 'select';


    public static function labels() : array
    {
        return [
            self::TEXT->value   => '输入',
            self::SELECT->value => '选择',

        ];

    }

    public static function colors() : array
    {
        return [
            self::TEXT->value   => 'info',
            self::SELECT->value => 'success',

        ];
    }
}
