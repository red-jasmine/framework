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

    case TICKET = 'ticket'; // 票据

    case SERVICE = 'service'; // 服务

    // 服务

    public static function labels() : array
    {
        return [
            self::GOODS->value   => __('red-jasmine-ecommerce::ecommerce.enums.product_type.goods'),
            self::VIRTUAL->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.virtual'),
            self::TICKET->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.ticket'),
            self::SERVICE->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.service'),
        ];
    }

    public static function icons() : array
    {
        return [
            self::GOODS->value   => 'heroicon-o-briefcase',
            self::VIRTUAL->value => 'heroicon-o-chart-bar-square',
            self::TICKET->value  => 'heroicon-o-ticket',
            self::SERVICE->value => 'heroicon-o-shield-check',

        ];
    }

    public static function shippingTypes() : array
    {
        return [


            self::GOODS->value => [
                ShippingTypeEnum::LOGISTICS->value,
                ShippingTypeEnum::DELIVERY->value,
                //ShippingTypeEnum::NONE->value,
                //ShippingTypeEnum::COUPONS->value,
                //ShippingTypeEnum::DUMMY->value,
                //ShippingTypeEnum::CARD_KEY->value,
            ],

            self::VIRTUAL->value => [
                //ShippingTypeEnum::LOGISTICS->value,
                //ShippingTypeEnum::DELIVERY->value,
                ShippingTypeEnum::NONE->value,
                ShippingTypeEnum::COUPONS->value,
                ShippingTypeEnum::DUMMY->value,
                ShippingTypeEnum::CARD_KEY->value,
            ],


            self::TICKET->value => [
                ShippingTypeEnum::LOGISTICS->value,
                ShippingTypeEnum::DELIVERY->value,
                ShippingTypeEnum::DUMMY->value,
                //ShippingTypeEnum::NONE->value,
                //ShippingTypeEnum::COUPONS->value,
                //ShippingTypeEnum::CARD_KEY->value,
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
}
