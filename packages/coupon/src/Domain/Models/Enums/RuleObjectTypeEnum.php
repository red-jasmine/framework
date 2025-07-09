<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RuleObjectTypeEnum: string
{
    use EnumsHelper;

    case PRODUCT = 'product';
    case CATEGORY = 'category';
    case BRAND = 'brand';
    case USER_GROUP = 'user_group';

    public static function labels() : array
    {
        return [
            self::PRODUCT->value    => __('red-jasmine-coupon::rule.enums.object_type.product'),
            self::CATEGORY->value   => __('red-jasmine-coupon::rule.enums.object_type.category'),
            self::BRAND->value      => __('red-jasmine-coupon::rule.enums.object_type.brand'),
            self::USER_GROUP->value => __('red-jasmine-coupon::rule.enums.object_type.user_group'),
        ];
    }

    public static function colors() : array
    {
        return [
            self::PRODUCT->value    => 'primary',
            self::CATEGORY->value   => 'secondary',
            self::BRAND->value      => 'info',
            self::USER_GROUP->value => 'warning',
        ];
    }

    public static function icons() : array
    {
        return [
            self::PRODUCT->value    => 'heroicon-o-cube',
            self::CATEGORY->value   => 'heroicon-o-folder',
            self::BRAND->value      => 'heroicon-o-tag',
            self::USER_GROUP->value => 'heroicon-o-user-group',
        ];
    }
} 