<?php

namespace RedJasmine\Support\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum BoolIntEnum: int
{

    use EnumsHelper;

    case  YES = 1;

    case NO = 0;


    public static function names() : array
    {
        return [
            self::YES->value => '是',
            self::NO->value  => '否',
        ];
    }


}
