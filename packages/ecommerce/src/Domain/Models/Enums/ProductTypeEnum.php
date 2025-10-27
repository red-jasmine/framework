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

    case CARD_KEY = 'cardKey'; // 数字卡密 数字 Digital

    case COUPONS = 'coupons'; // 卡券




    public function getAllowShippingType(): array
    {
        return ProductTypeEnum::shippingTypes()[$this->value];
    }

    /**
     * 是否需要收货地址
     * @return bool
     */
    public function isNeedDeliveryAddress(): bool
    {
        return ($this->value === self::PHYSICAL->value);
    }

    /**
     * 是否允许选择配送方式
     * @return bool
     */
    public function isAllowDeliveryMethods(): bool
    {
        return ($this->value === self::PHYSICAL->value);
    }


    public static function shippingTypes(): array
    {
        return [


            self::PHYSICAL->value => [
                ShippingTypeEnum::LOGISTICS,
                ShippingTypeEnum::DELIVERY,
                ShippingTypeEnum::SELF_PICKUP,
                //ShippingTypeEnum::NONE->value,
                //ShippingTypeEnum::COUPONS->value,
                //ShippingTypeEnum::DUMMY->value,
                //ShippingTypeEnum::CARD_KEY->value,
            ],

            self::VIRTUAL->value => [
                //ShippingTypeEnum::LOGISTICS->value,
                //ShippingTypeEnum::DELIVERY->value,
                //ShippingTypeEnum::NONE->value,
                ShippingTypeEnum::DUMMY,
                //ShippingTypeEnum::CARD_KEY->value,
            ],
            self::CARD_KEY->value => [
                //ShippingTypeEnum::LOGISTICS->value,
                //ShippingTypeEnum::DELIVERY->value,
                //ShippingTypeEnum::NONE->value,
                //ShippingTypeEnum::DUMMY->value,
                ShippingTypeEnum::CARD_KEY,
            ],


            self::COUPONS->value => [
                ShippingTypeEnum::COUPONS,
                //ShippingTypeEnum::LOGISTICS->value,
                //ShippingTypeEnum::DELIVERY->value,
            ],


            self::SERVICE->value => [
                //ShippingTypeEnum::LOGISTICS->value,
                //ShippingTypeEnum::DELIVERY->value,
                ShippingTypeEnum::NONE,
                //ShippingTypeEnum::COUPONS->value,
                //ShippingTypeEnum::DUMMY->value,
                //ShippingTypeEnum::CARD_KEY->value,
            ],


        ];
    }

    // 服务

    public static function labels(): array
    {
        return [
            self::PHYSICAL->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.goods'),
            self::VIRTUAL->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.virtual'),
            self::CARD_KEY->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.cardKey'),
            self::COUPONS->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.coupons'),
            self::SERVICE->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.service'),
        ];
    }

    public static function icons(): array
    {
        return [
            self::PHYSICAL->value => 'heroicon-o-shopping-bag',
            self::VIRTUAL->value  => 'heroicon-o-cloud',
            self::COUPONS->value  => 'heroicon-o-ticket',
            self::CARD_KEY->value => 'heroicon-o-key',
            self::SERVICE->value  => 'heroicon-o-shield-check',

        ];
    }

    public static function tips(): array
    {
        return [];
    }


}
