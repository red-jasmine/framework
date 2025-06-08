<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 商品 类型
 */
enum ProductTypeEnum: string
{
    use EnumsHelper;

    case GOODS = 'goods'; // 实物

    case VIRTUAL = 'virtual'; // 虚拟

    case CardKey = 'cardKey'; // 数字卡密

    case COUPONS = 'coupons'; // 卡券

    case SERVICE = 'service'; // 服务

    public static function shippingTypes() : array
    {
        return [


            self::GOODS->value => [
                ShippingTypeEnum::LOGISTICS->value,
                ShippingTypeEnum::DELIVERY->value,
                ShippingTypeEnum::SELF_PICKUP->value,
                //ShippingTypeEnum::NONE->value,
                //ShippingTypeEnum::COUPONS->value,
                //ShippingTypeEnum::DUMMY->value,
                //ShippingTypeEnum::CARD_KEY->value,
            ],

            self::VIRTUAL->value => [
                //ShippingTypeEnum::LOGISTICS->value,
                //ShippingTypeEnum::DELIVERY->value,
                //ShippingTypeEnum::NONE->value,
                ShippingTypeEnum::DUMMY->value,
                ShippingTypeEnum::CARD_KEY->value,
            ],


            self::COUPONS->value => [
                ShippingTypeEnum::COUPONS->value,
                ShippingTypeEnum::LOGISTICS->value,
                ShippingTypeEnum::DELIVERY->value,
            ],


            self::SERVICE->value => [
                //ShippingTypeEnum::LOGISTICS->value,
                //ShippingTypeEnum::DELIVERY->value,
                ShippingTypeEnum::NONE->value,
                //ShippingTypeEnum::COUPONS->value,
                //ShippingTypeEnum::DUMMY->value,
                //ShippingTypeEnum::CARD_KEY->value,
            ],


        ];
    }

    // 服务

    public static function labels() : array
    {
        return [
            self::GOODS->value   => __('red-jasmine-ecommerce::ecommerce.enums.product_type.goods'),
            self::VIRTUAL->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.virtual'),
            self::CardKey->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.cardKey'),
            self::COUPONS->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.coupons'),
            self::SERVICE->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.service'),
        ];
    }

    public static function icons() : array
    {
        return [
            self::GOODS->value   => 'heroicon-o-shopping-bag',
            self::VIRTUAL->value => 'heroicon-o-chart-bar-square',
            self::COUPONS->value => 'heroicon-o-ticket',
            self::CardKey->value => 'heroicon-o-key',
            self::SERVICE->value => 'heroicon-o-shield-check',

        ];
    }

    public static function tips() : array
    {
        return [];
    }


}
