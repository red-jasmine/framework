<?php

namespace RedJasmine\Order\Domain\Flows;

use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderProduct;

/**
 * 订单预售流程
 */
class OrderPresaleFlow extends OrderStandardFlow implements OrderFlowInterface
{
    public function paid(Order $order) : void
    {
        // 部分支付
        if ($order->payment_status === PaymentStatusEnum::PART_PAY) {
            $order->order_status = OrderStatusEnum::WAIT_SELLER_SEND_GOODS;
            $order->products->each(function (OrderProduct $product) {
                // 准备发货
                $product->order_status    = OrderStatusEnum::WAIT_SELLER_SEND_GOODS;
                $product->shipping_status = ShippingStatusEnum::READY_SEND;
            });
            return;
        }

        // 全部支付
        if ($order->payment_status === PaymentStatusEnum::PAID) {
            $order->order_status = OrderStatusEnum::WAIT_SELLER_SEND_GOODS;
            $order->products->each(function (OrderProduct $product) {
                // 等待发货
                $product->order_status    = OrderStatusEnum::WAIT_SELLER_SEND_GOODS;
                $product->shipping_status = ShippingStatusEnum::WAIT_SEND;
            });
        }


    }

}
