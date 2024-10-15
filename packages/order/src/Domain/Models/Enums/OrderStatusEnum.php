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
    case  WAIT_SELLER_SEND_GOODS = 'wait_seller_send_goods'; // 待发货
    case  WAIT_BUYER_CONFIRM_GOODS = 'wait_buyer_confirm_goods'; // 待收货
    case  FINISHED = 'finished'; // 交易成功
    case  CANCEL = 'cancel'; //已取消 未支付
    case  CLOSED = 'closed'; // 已关闭 已支付已退款


    public static function labels() : array
    {
        return [
            self::WAIT_BUYER_PAY->value           => '待付款',
            self::WAIT_SELLER_SEND_GOODS->value   => '待发货',
            self::WAIT_BUYER_CONFIRM_GOODS->value => '待收货',
            self::FINISHED->value                 => '已完成',
            self::CANCEL->value                   => '已取消',
            self::CLOSED->value                   => '已关闭',
        ];
    }
}
