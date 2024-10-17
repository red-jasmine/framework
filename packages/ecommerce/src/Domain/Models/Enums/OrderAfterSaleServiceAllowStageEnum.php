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

    // 支付后
    case  PAYED = 'payed';

    case  SHIPPING = 'shipping';
    // 发货后
    case  SHIPPED = 'shipped';
    // 签收后
    case  SIGNED = 'signed';

    // 完成后
    case  COMPLETED = 'completed';


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
