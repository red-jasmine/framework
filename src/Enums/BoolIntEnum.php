<?php

namespace RedJasmine\Support\Enums;

enum BoolIntEnum: int
{
    case  YES = 1;

    case NO = 0;


    public static function options() : array
    {
        return [
            self::YES->value => '是',
            self::NO->value  => '否',
        ];
    }


}
