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

    case  VIRTUAL = 'virtual';  // 虚拟发货

    case CDK = 'cdk'; // 卡密

    case DELIVERY = 'delivery'; // 配送

    case NIL = 'nil'; // 无需发货


    public static function labels() : array
    {
        return [
            self::EXPRESS->value  => '快递',
            self::CDK->value      => '卡密',
            self::VIRTUAL->value  => '虚拟',
            self::DELIVERY->value => '配送',
            self::NIL->value      => '免发货',
        ];

    }
}
