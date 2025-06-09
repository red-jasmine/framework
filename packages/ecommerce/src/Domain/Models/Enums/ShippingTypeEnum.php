<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use Illuminate\Support\Arr;
use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 发货类型
 */
enum ShippingTypeEnum: string
{
    use EnumsHelper;


    case LOGISTICS = 'logistics'; // 物流快递

    case DELIVERY = 'delivery'; // 配送

    case SELF_PICKUP = 'selfPickup'; //自己提货


    case  DUMMY = 'dummy';  // 虚拟发货

    case CARD_KEY = 'cardKey'; // 卡密

    case COUPONS = 'coupons'; // 卡券

    case NONE = 'none'; // 无需发货


    public static function deliveryMethods() : array
    {
        return Arr::only(self::labels(), [
            self::LOGISTICS->value,
            self::DELIVERY->value,
            self::SELF_PICKUP->value,
        ]);
    }

    public static function labels() : array
    {
        return [
            self::LOGISTICS->value   => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.logistics'),
            self::CARD_KEY->value    => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.cardKey'),
            self::DUMMY->value       => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.dummy'),
            self::DELIVERY->value    => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.delivery'),
            self::COUPONS->value     => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.coupons'),
            self::SELF_PICKUP->value => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.selfPickup'),
            self::NONE->value        => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.none'),
        ];
    }

    public static function icons() : array
    {
        return [
            self::LOGISTICS->value   => 'heroicon-o-truck',
            self::DUMMY->value       => 'heroicon-o-bolt',
            self::CARD_KEY->value    => 'heroicon-o-key',
            self::DELIVERY->value    => 'heroicon-o-home-modern',
            self::COUPONS->value     => 'heroicon-o-ticket',
            self::SELF_PICKUP->value => 'heroicon-o-building-storefront',
            self::NONE->value        => 'heroicon-o-cloud',

        ];
    }


    public static function allowLogistics() : array
    {
        return [
            self::LOGISTICS,
            self::DELIVERY,
        ];
    }
}
