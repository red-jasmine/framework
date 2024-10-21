<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 订单类型
 */
enum OrderTypeEnum: string
{
    use EnumsHelper;

    case  STANDARD = 'standard';

    case  PRESALE = 'presale';

    //case  GROUP_PURCHASE = 'group_purchase';

    // TODO 拍卖


    public static function labels() : array
    {
        return [
            self::STANDARD->value => __('red-jasmine-order::order.enums.order_type.standard'),
            self::PRESALE->value  => __('red-jasmine-order::order.enums.order_type.presale'),
            //self::GROUP_PURCHASE->value => __('red-jasmine-order::order.enums.order_type.group_purchase'),
        ];
    }




}
