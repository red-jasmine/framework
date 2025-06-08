<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;


use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 退款类型
 */
enum RefundTypeEnum: string
{
    use EnumsHelper;

    // 仅退款
    case  REFUND = 'refund';
    // 退货退款
    case  RETURN_GOODS_REFUND = 'return_goods_refund';
    // 换货
    case  EXCHANGE = 'exchange';
    // 保修
    case  WARRANTY = 'warranty';
    // 补发
    case  RESHIPMENT = 'reshipment';


    public static function labels() : array
    {
        return [
            self::REFUND->value              => __('red-jasmine-ecommerce::ecommerce.enums.refund_type.refund'),
            self::RETURN_GOODS_REFUND->value => __('red-jasmine-ecommerce::ecommerce.enums.refund_type.return_goods_refund'),
            self::EXCHANGE->value            => __('red-jasmine-ecommerce::ecommerce.enums.refund_type.exchange'),
            self::WARRANTY->value            => __('red-jasmine-ecommerce::ecommerce.enums.refund_type.warranty'),
            self::RESHIPMENT->value          => __('red-jasmine-ecommerce::ecommerce.enums.refund_type.reshipment'),
        ];

    }




    /**
     * 基础售后类型
     * @return RefundTypeEnum[]
     */
    public static function baseTypes() : array
    {
        return [
            self::REFUND,
            self::EXCHANGE,
            self::WARRANTY,
        ];
    }
}
