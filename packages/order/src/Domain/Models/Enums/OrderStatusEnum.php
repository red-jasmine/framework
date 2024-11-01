<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 订单状态
 */
enum OrderStatusEnum: string
{
    use EnumsHelper;

    case  WAIT_BUYER_PAY = 'wait_buyer_pay'; // 待付款
    case  WAIT_SELLER_ACCEPT = 'wait_seller_accept'; // 待接单
    case  WAIT_SELLER_SEND_GOODS = 'wait_seller_send_goods'; // 待发货
    case  WAIT_BUYER_CONFIRM_GOODS = 'wait_buyer_confirm_goods'; // 待收货
    case  FINISHED = 'finished'; // 交易成功
    case  CANCEL = 'cancel'; //已取消 未支付
    case  CLOSED = 'closed'; // 已关闭 已支付已退款


    public static function labels() : array
    {
        return [
            self::WAIT_BUYER_PAY->value           => __('red-jasmine-order::order.enums.order_status.wait_buyer_pay'),
            self::WAIT_SELLER_ACCEPT->value       => __('red-jasmine-order::order.enums.order_status.wait_seller_accept'),
            self::WAIT_SELLER_SEND_GOODS->value   => __('red-jasmine-order::order.enums.order_status.wait_seller_send_goods'),
            self::WAIT_BUYER_CONFIRM_GOODS->value => __('red-jasmine-order::order.enums.order_status.wait_buyer_confirm_goods'),
            self::FINISHED->value                 => __('red-jasmine-order::order.enums.order_status.finished'),
            self::CANCEL->value                   => __('red-jasmine-order::order.enums.order_status.cancel'),
            self::CLOSED->value                   => __('red-jasmine-order::order.enums.order_status.closed'),
        ];
    }

    public static function icons() : array
    {
        return [

            self::WAIT_BUYER_PAY->value           => 'heroicon-o-banknotes',
            self::WAIT_SELLER_ACCEPT->value       => 'heroicon-o-bell-alert',
            self::WAIT_SELLER_SEND_GOODS->value   => 'heroicon-o-arrow-up-on-square-stack',
            self::WAIT_BUYER_CONFIRM_GOODS->value => 'heroicon-o-truck',
            self::FINISHED->value                 => 'heroicon-o-shield-check',
            self::CANCEL->value                   => 'heroicon-o-archive-box-x-mark',
            self::CLOSED->value                   => 'heroicon-o-x-circle',

        ];
    }

    public static function colors() : array
    {
        return [

            self::WAIT_BUYER_PAY->value           => 'warning',
            self::WAIT_SELLER_ACCEPT->value       => 'danger',
            self::WAIT_SELLER_SEND_GOODS->value   => 'danger',
            self::WAIT_BUYER_CONFIRM_GOODS->value => 'success',
            self::FINISHED->value                 => 'success',
            self::CANCEL->value                   => 'gray',
            self::CLOSED->value                   => 'warning',

        ];
    }
}
