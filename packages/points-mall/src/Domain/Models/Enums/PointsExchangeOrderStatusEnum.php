<?php

namespace RedJasmine\PointsMall\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PointsExchangeOrderStatusEnum: string
{
    use EnumsHelper;


    case PAYING = 'paying';


    case PAID = 'paid';


    public static function labels() : array
    {
        return [
            self::PAYING->value => '待支付',
            self::PAID->value   => '已支付',

        ];
    }

    public static function colors() : array
    {
        return [
            self::PAYING->value => 'warning',
            self::PAID->value   => 'success',
        ];
    }

    public static function icons() : array
    {
        return [
            self::PAYING->value => 'heroicon-o-credit-card',
            self::PAID->value   => 'heroicon-o-truck',
        ];
    }


}