<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 订单状态
 */
enum OrderStatusEnum: string
{
    use EnumsHelper;

    case  PAYING = 'paying'; // 待付款
    case  ACCEPTING = 'accepting'; // 待接单
    case  SHIPPING = 'shipping'; // 待发货
    case  CONFIRMING = 'confirming'; // 待收货
    case  FINISHED = 'finished'; // 交易成功
    case  CANCEL = 'cancel'; //已取消 未支付
    case  CLOSED = 'closed'; // 已关闭 已支付已退款


    public static function labels() : array
    {
        return [
            self::PAYING->value     => __('red-jasmine-order::order.enums.order_status.paying'),
            self::ACCEPTING->value  => __('red-jasmine-order::order.enums.order_status.accepting'),
            self::SHIPPING->value   => __('red-jasmine-order::order.enums.order_status.shipping'),
            self::CONFIRMING->value => __('red-jasmine-order::order.enums.order_status.confirming'),
            self::FINISHED->value   => __('red-jasmine-order::order.enums.order_status.finished'),
            self::CANCEL->value     => __('red-jasmine-order::order.enums.order_status.cancel'),
            self::CLOSED->value     => __('red-jasmine-order::order.enums.order_status.closed'),
        ];
    }

    public static function icons() : array
    {
        return [

            self::PAYING->value     => 'heroicon-o-banknotes',
            self::ACCEPTING->value  => 'heroicon-o-bell-alert',
            self::SHIPPING->value   => 'heroicon-o-arrow-up-on-square-stack',
            self::CONFIRMING->value => 'heroicon-o-truck',
            self::FINISHED->value   => 'heroicon-o-shield-check',
            self::CANCEL->value     => 'heroicon-o-archive-box-x-mark',
            self::CLOSED->value     => 'heroicon-o-x-circle',

        ];
    }

    public static function colors() : array
    {
        return [

            self::PAYING->value     => 'warning',
            self::ACCEPTING->value  => 'danger',
            self::SHIPPING->value   => 'danger',
            self::CONFIRMING->value => 'success',
            self::FINISHED->value   => 'success',
            self::CANCEL->value     => 'gray',
            self::CLOSED->value     => 'warning',

        ];
    }
}
