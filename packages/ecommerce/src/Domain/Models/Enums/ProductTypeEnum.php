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

    case BOOKING = 'booking'; // 预定类（酒店、民宿、门票、机票等）


    public static function labels() : array
    {
        return [
            self::VIRTUAL->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.virtual'),
            self::PHYSICAL->value => __('red-jasmine-ecommerce::ecommerce.enums.product_type.goods'),
            self::DIGITAL->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.digital'),
            self::COUPONS->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.coupons'),
            self::SERVICE->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.service'),
            self::BOOKING->value  => __('red-jasmine-ecommerce::ecommerce.enums.product_type.booking'),
        ];
    }

    public static function icons() : array
    {
        return [
            self::PHYSICAL->value => 'emoji-shopping-bags',
            self::VIRTUAL->value  => 'emoji-globe-with-meridians',
            self::COUPONS->value  => 'emoji-admission-tickets',
            self::DIGITAL->value  => 'emoji-left-luggage',
            self::SERVICE->value  => 'emoji-woman-teacher',
            self::BOOKING->value  => 'emoji-hotel',

        ];
    }

    public static function tips() : array
    {
        return [];
    }

    public function defaultShippingTypes() : array
    {
        return $this->getAllowShippingTypes();
    }

    public function getAllowShippingTypes() : array
    {
        return ProductTypeEnum::shippingTypeList()[$this->value];
    }

    protected static function shippingTypeList() : array
    {

        return [

            // 实物
            self::PHYSICAL->value => [
                ShippingTypeEnum::LOGISTICS,
                ShippingTypeEnum::DELIVERY,
                ShippingTypeEnum::INSTORE,

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

            // 预定类（酒店、民宿、门票、机票等）
            self::BOOKING->value => [
                ShippingTypeEnum::INSTORE,  // 到店（酒店入住、景点入园等）
                ShippingTypeEnum::VISIT,     // 上门服务（接送服务等）
                ShippingTypeEnum::NONE,      // 无需配送（电子凭证）
            ],
        ];
    }

    // 服务

    public function shippingTypes() : array
    {

        return ProductTypeEnum::shippingTypeList()[$this->value];
    }

    /**
     * 是否需要收货地址
     * @return bool
     */
    public function isNeedDeliveryAddress() : bool
    {
        return match ($this) {
            self::PHYSICAL => true,
            self::BOOKING => false, // 预定类不需要传统收货地址，但可能需要服务地址
            default => false,
        };
    }

    /**
     * 是否允许选择配送方式
     * @return bool
     */
    public function isAllowDeliveryMethods() : bool
    {
        return match ($this) {
            self::PHYSICAL => true,
            self::BOOKING => false, // 预定类通过服务方式选择，而非配送方式
            default => false,
        };
    }

    /**
     * 是否需要时间预约（入住日期、退房日期等）
     * @return bool
     */
    public function isNeedTimeBooking() : bool
    {
        return match ($this) {
            self::BOOKING => true,
            default => false,
        };
    }


}
