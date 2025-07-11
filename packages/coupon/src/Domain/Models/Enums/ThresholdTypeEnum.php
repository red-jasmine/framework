<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 门槛类型枚举
 */
enum ThresholdTypeEnum: string
{
    use EnumsHelper;


    case AMOUNT = 'amount';
    case QUANTITY = 'quantity';

    public static function labels() : array
    {
        return [
            self::AMOUNT->value   => __('red-jasmine-coupon::coupon.enums.threshold_type.amount'),
            self::QUANTITY->value => __('red-jasmine-coupon::coupon.enums.threshold_type.quantity'),
        ];
    }

    public static function colors() : array
    {
        return [
            self::AMOUNT->value   => 'primary',
            self::QUANTITY->value => 'secondary',
        ];
    }

    public static function icons() : array
    {
        return [
            self::AMOUNT->value   => 'heroicon-o-currency-dollar',
            self::QUANTITY->value => 'heroicon-o-percent-badge',
        ];
    }
}
