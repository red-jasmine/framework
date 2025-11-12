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

    // 实物发货履约类

    case LOGISTICS = 'logistics'; // 物流快递

    case DELIVERY = 'delivery'; // 同城配送

    case PICKUP = 'pickup'; // 自提


    // 虚拟商品履约类

    case  DUMMY = 'dummy';  // 虚拟发货

    case DIGITAL = 'digital'; // 数字卡

    case COUPONS = 'coupons'; // 卡券


    // 餐饮类
    case DINE = 'dine';                  // 堂食
    case TAKEAWAY = 'takeaway';          // 打包

    // 服务类 履约 有 上门、到店

    case VISIT = 'visit';        // 上门服务

    case INSTORE = 'instore';    // 到店服务

    // 无需发货
    case NONE = 'none'; // 无需发货


    public static function deliveryMethods() : array
    {
        return Arr::only(self::labels(), [
            self::LOGISTICS->value,
            self::DELIVERY->value,
            self::PICKUP->value,
        ]);
    }

    public static function labels() : array
    {
        return [
            self::LOGISTICS->value => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.logistics'),
            self::DIGITAL->value   => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.digital'),
            self::DUMMY->value     => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.dummy'),
            self::DELIVERY->value  => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.delivery'),
            self::COUPONS->value   => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.coupons'),
            self::PICKUP->value    => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.pickup'),
            self::DINE->value      => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.dine'),
            self::TAKEAWAY->value  => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.takeaway'),
            self::VISIT->value     => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.visit'),
            self::INSTORE->value   => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.instore'),
            self::NONE->value      => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.none'),
        ];
    }

    public static function icons() : array
    {
        return [
            self::LOGISTICS->value => 'heroicon-o-truck',
            self::DUMMY->value     => 'heroicon-o-bolt',
            self::DIGITAL->value   => 'heroicon-o-key',
            self::DELIVERY->value  => 'heroicon-o-home-modern',
            self::COUPONS->value   => 'heroicon-o-ticket',
            self::PICKUP->value    => 'heroicon-o-building-storefront',
            self::DINE->value      => 'heroicon-o-bowl-food',
            self::TAKEAWAY->value  => 'heroicon-o-shopping-bag',
            self::VISIT->value     => 'heroicon-o-home',
            self::INSTORE->value   => 'heroicon-o-building-storefront',
            self::NONE->value      => 'heroicon-o-cloud',

        ];
    }

    public static function allowLogistics() : array
    {
        return [
            self::LOGISTICS,
            self::DELIVERY,
        ];
    }

    /**
     * 是否需要收货地址
     * @return bool
     */
    public function requiresAddress() : bool
    {
        return match ($this) {
            self::LOGISTICS,
            self::DELIVERY,
            self::VISIT => true,
            self::PICKUP,
            self::TAKEAWAY,
            self::DINE,
            self::INSTORE,
            self::DUMMY,
            self::DIGITAL,
            self::COUPONS,
            self::NONE => false,
        };
    }

    /**
     * 是否需要物流运输
     * @return bool
     */
    public function requiresShipping() : bool
    {
        return match ($this) {
            self::LOGISTICS,
            self::DELIVERY => true,
            self::PICKUP,
            self::DINE,
            self::TAKEAWAY,
            self::VISIT,
            self::INSTORE,
            self::DUMMY,
            self::DIGITAL,
            self::COUPONS,
            self::NONE => false,
        };
    }

    /**
     * 是否需要计算运费
     * @return bool
     */
    public function requiresFreight() : bool
    {
        return match ($this) {
            self::LOGISTICS,
            self::DELIVERY => true,
            self::PICKUP,
            self::DINE,
            self::TAKEAWAY,
            self::VISIT,
            self::INSTORE,
            self::DUMMY,
            self::DIGITAL,
            self::COUPONS,
            self::NONE => false,
        };
    }
}
