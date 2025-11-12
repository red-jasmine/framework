<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 商品 类型
 */
enum ProductTypeEnum: string
{
    use EnumsHelper;

    case PHYSICAL = 'physical'; // 实物/ 物理 Physical

    case VIRTUAL = 'virtual'; // 虚拟

    case SERVICE = 'service'; // 服务

    case DIGITAL = 'digital'; // 数字卡密 数字 DIGITAL

    case COUPONS = 'coupons'; // 卡券

    case FOOD = 'food'; // 食品


    public static function labels() : array
    {
        return [
            self::PHYSICAL->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.goods'),
            self::VIRTUAL->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.virtual'),
            self::DIGITAL->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.digital'),
            self::COUPONS->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.coupons'),
            self::SERVICE->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.service'),
            self::FOOD->value     => __('red-jasmine-ecommerce::ecommerce.enums.product_type.food'),
        ];
    }

    public static function icons() : array
    {
        return [
            self::PHYSICAL->value => 'heroicon-o-shopping-bag',
            self::VIRTUAL->value  => 'heroicon-o-cloud',
            self::COUPONS->value  => 'heroicon-o-ticket',
            self::DIGITAL->value  => 'heroicon-o-key',
            self::SERVICE->value  => 'heroicon-o-shield-check',
            self::FOOD->value     => 'heroicon-o-gift-top',

        ];
    }

    public static function tips() : array
    {
        return [];
    }

    public function getAllowShippingType() : array
    {
        return ProductTypeEnum::shippingTypes()[$this->value];
    }

    // 服务

    public static function shippingTypes() : array
    {

        return [

            // 实物
            self::PHYSICAL->value => [
                ShippingTypeEnum::LOGISTICS,
                ShippingTypeEnum::DELIVERY,
                ShippingTypeEnum::PICKUP,

            ],

            self::VIRTUAL->value => [
                ShippingTypeEnum::DUMMY,
            ],
            self::DIGITAL->value => [
                ShippingTypeEnum::DIGITAL,
            ],


            self::COUPONS->value => [
                ShippingTypeEnum::COUPONS,
            ],


            self::SERVICE->value => [

                ShippingTypeEnum::VISIT,
                ShippingTypeEnum::INSTORE,
                ShippingTypeEnum::NONE,

            ],

            self::FOOD->value => [
                ShippingTypeEnum::DELIVERY,
                ShippingTypeEnum::TAKEAWAY,
                ShippingTypeEnum::DINE,
            ],
        ];
    }

    /**
     * 是否需要收货地址
     * @return bool
     */
    public function isNeedDeliveryAddress() : bool
    {
        return ($this->value === self::PHYSICAL->value);
    }

    /**
     * 是否允许选择配送方式
     * @return bool
     */
    public function isAllowDeliveryMethods() : bool
    {
        return ($this->value === self::PHYSICAL->value);
    }


}
