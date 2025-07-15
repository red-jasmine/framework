<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;


enum DiscountLevelEnum: string
{
    use EnumsHelper;

    case PRODUCT = 'product';
    case ORDER = 'order';
    case CHECKOUT = 'checkout';
    case SHIPPING = 'shipping';


    public static function labels() : array
    {
        return [
            self::ORDER->value    => __('red-jasmine-coupon::ecommerce.enums.discount_level.order_amount'),
            self::PRODUCT->value  => __('red-jasmine-coupon::ecommerce.enums.discount_level.product_amount'),
            self::CHECKOUT->value => __('red-jasmine-coupon::ecommerce.enums.discount_level.checkout_amount'),
            self::SHIPPING->value => __('red-jasmine-coupon::ecommerce.enums.discount_level.shipping_amount'),

        ];
    }

    public static function colors() : array
    {
        return [
            self::ORDER->value    => 'primary',
            self::PRODUCT->value  => 'secondary',
            self::SHIPPING->value => 'info',
            self::CHECKOUT->value => 'warning',
        ];
    }

    public static function icons() : array
    {
        return [
            self::ORDER->value    => 'heroicon-o-shopping-cart',
            self::PRODUCT->value  => 'heroicon-o-cube',
            self::SHIPPING->value => 'heroicon-o-truck',
            self::CHECKOUT->value => 'heroicon-o-building-storefront',
        ];
    }
} 