<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum DiscountAmountTypeEnum: string
{
    use EnumsHelper;


    public static function title() : string
    {
        return '优惠金额类型';
    }

    case FIXED_AMOUNT = 'fixed_amount';
    case PERCENTAGE = 'percentage';

    public static function labels() : array
    {
        return [
            self::FIXED_AMOUNT->value => __('red-jasmine-coupon::discount.enums.type.fixed_amount'),
            self::PERCENTAGE->value   => __('red-jasmine-coupon::discount.enums.type.percentage'),
        ];
    }

    public static function colors() : array
    {
        return [
            self::FIXED_AMOUNT->value => 'primary',
            self::PERCENTAGE->value   => 'secondary',
        ];
    }

    public static function icons() : array
    {
        return [
            self::FIXED_AMOUNT->value => 'heroicon-o-currency-dollar',
            self::PERCENTAGE->value   => 'heroicon-o-percent-badge',
        ];
    }
} 