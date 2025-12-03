<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 售后阶段
 */
enum OrderAfterSaleServiceAllowStageEnum: string
{
    use EnumsHelper;


    case  NEVER = 'never';

    case  ALL = 'all'; // 全阶段

    // 支付后
    case  PAYED = 'payed'; // 下单后 、发货前

    case  SHIPPING = 'shipping'; // 开始货后
    // 发货后
    case  SHIPPED = 'shipped'; //  完成发货后
    // 签收后
    case  SIGNED = 'signed'; // 签收后

    // 完成后
    case  COMPLETED = 'completed'; // 确认后


    public static function labels() : array
    {
        return [
            self::NEVER->value     => '不支持',
            self::PAYED->value     => '支付后',
            self::SHIPPING->value  => '开始发货后',
            self::SHIPPED->value   => '完成发货后',
            self::SIGNED->value    => '签收后',
            self::COMPLETED->value => '确认后',
        ];
    }


}
