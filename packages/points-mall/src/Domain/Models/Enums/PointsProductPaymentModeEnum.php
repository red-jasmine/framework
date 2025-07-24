<?php

namespace RedJasmine\PointsMall\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PointsProductPaymentModeEnum: string
{
    use EnumsHelper;

    case POINTS = 'points'; // 纯积分支付 无需感知商品金额

    case MIXED = 'mixed'; // 积分 + 固定金额 ,无需感知 原有商品金额

    public static function labels() : array
    {
        return [
            self::POINTS->value => '纯积分支付',
            self::MIXED->value  => '积分+金额',
        ];
    }

    public static function colors() : array
    {
        return [
            self::POINTS->value => 'blue',
            self::MIXED->value  => 'purple',
        ];
    }

    public static function icons() : array
    {
        return [
            self::POINTS->value => 'heroicon-o-star',
            self::MIXED->value  => 'heroicon-o-currency-dollar',
        ];
    }
} 