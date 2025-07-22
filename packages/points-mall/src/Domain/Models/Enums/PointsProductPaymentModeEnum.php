<?php

namespace RedJasmine\PointsMall\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PointsProductPaymentModeEnum: string
{
    use EnumsHelper;

    case POINTS_ONLY = 'points_only';
    case MONEY_ONLY = 'money_only';
    case MIXED = 'mixed';

    public static function labels(): array
    {
        return [
            self::POINTS_ONLY->value => '纯积分支付',
            self::MONEY_ONLY->value => '纯现金支付',
            self::MIXED->value => '积分+现金混合支付',
        ];
    }

    public static function colors(): array
    {
        return [
            self::POINTS_ONLY->value => 'blue',
            self::MONEY_ONLY->value => 'green',
            self::MIXED->value => 'purple',
        ];
    }

    public static function icons(): array
    {
        return [
            self::POINTS_ONLY->value => 'heroicon-o-star',
            self::MONEY_ONLY->value => 'heroicon-o-currency-dollar',
            self::MIXED->value => 'heroicon-o-currency-dollar',
        ];
    }
} 