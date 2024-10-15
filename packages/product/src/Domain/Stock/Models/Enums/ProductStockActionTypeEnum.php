<?php

namespace RedJasmine\Product\Domain\Stock\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStockActionTypeEnum: string
{

    use EnumsHelper;


    case ADD = 'add';
    case SUB = 'sub';
    case RESET = 'reset';
    case LOCK = 'lock';
    case UNLOCK = 'unlock';
    case CONFIRM = 'confirm';


    public static function labels() : array
    {
        return [
            self::RESET->value   => '设置',
            self::ADD->value     => '增加',
            self::SUB->value     => '扣减',
            self::LOCK->value    => '锁定',
            self::UNLOCK->value  => '解锁',
            self::CONFIRM->value => '确认',

        ];

    }


    public static function allowActionTypes() : array
    {
        return [
            self::RESET->value => '设置',
            self::ADD->value   => '增加',
            self::SUB->value   => '扣减',
        ];
    }


}
