<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 发货类型
 */
enum ShippingTypeEnum: string
{
    use EnumsHelper;

    case EXPRESS = 'express'; // 物流快递

    case  DUMMY = 'dummy';  // 虚拟发货

    case CDK = 'cdk'; // 卡密

    case DELIVERY = 'delivery'; // 配送

    case NONE = 'none'; // 没有的


    public static function labels() : array
    {
        return [
            self::EXPRESS->value  => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.express'),
            self::CDK->value      => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.cdk'),
            self::DUMMY->value    => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.dummy'),
            self::DELIVERY->value => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.delivery'),
            self::NONE->value     => __('red-jasmine-ecommerce::ecommerce.enums.shipping_type.none'),
        ];
    }

    public static function icons() : array
    {
        return [
            self::EXPRESS->value  => 'heroicon-o-truck',
            self::DUMMY->value    => 'heroicon-o-bolt',
            self::CDK->value      => 'heroicon-o-key',
            self::DELIVERY->value => 'heroicon-o-home-modern',
            self::NONE->value     => 'heroicon-o-cloud',

        ];
    }
}
