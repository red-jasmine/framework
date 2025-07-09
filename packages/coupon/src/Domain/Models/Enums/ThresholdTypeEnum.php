<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ThresholdTypeEnum: string
{
    use EnumsHelper;

    case ORDER_AMOUNT = 'order_amount';
    case PRODUCT_AMOUNT = 'product_amount';
    case SHIPPING_AMOUNT = 'shipping_amount';
    case CROSS_STORE_AMOUNT = 'cross_store_amount';

    public static function labels(): array
    {
        return [
            self::ORDER_AMOUNT->value => __('red-jasmine-coupon::threshold.enums.type.order_amount'),
            self::PRODUCT_AMOUNT->value => __('red-jasmine-coupon::threshold.enums.type.product_amount'),
            self::SHIPPING_AMOUNT->value => __('red-jasmine-coupon::threshold.enums.type.shipping_amount'),
            self::CROSS_STORE_AMOUNT->value => __('red-jasmine-coupon::threshold.enums.type.cross_store_amount'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::ORDER_AMOUNT->value => 'primary',
            self::PRODUCT_AMOUNT->value => 'secondary',
            self::SHIPPING_AMOUNT->value => 'info',
            self::CROSS_STORE_AMOUNT->value => 'warning',
        ];
    }

    public static function icons(): array
    {
        return [
            self::ORDER_AMOUNT->value => 'heroicon-o-shopping-cart',
            self::PRODUCT_AMOUNT->value => 'heroicon-o-cube',
            self::SHIPPING_AMOUNT->value => 'heroicon-o-truck',
            self::CROSS_STORE_AMOUNT->value => 'heroicon-o-building-storefront',
        ];
    }
} 