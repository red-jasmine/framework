<?php

namespace RedJasmine\Order\Domain\Services;

use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Order\Domain\Models\OrderRefund;

class OrderRefundService
{

    /**
     * @param  Order  $order
     * @param  OrderRefund  $orderRefund
     *
     * @return OrderRefund
     * @throws RefundException
     */
    public function create(Order $order, OrderRefund $orderRefund) : OrderRefund
    {
        /**
         * @var $orderProduct OrderProduct
         */
        $orderProduct = $orderRefund->product;
        // 如果存在退款单 单 那么不允许创建 TODO
        // 类型是否允许
        if (!in_array($orderRefund->refund_type, $orderProduct->allowRefundTypes(), true)) {
            throw RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }
        // TODO


        // 填充 子商品单
        $orderRefund->app_id                 = $order->app_id;
        $orderRefund->order_no               = $order->order_no;
        $orderRefund->seller                 = $order->seller;
        $orderRefund->buyer                  = $order->buyer;
        $orderRefund->shipping_type          = $orderProduct->shipping_type;
        $orderRefund->order_product_type     = $orderProduct->order_product_type;
        $orderRefund->product_type           = $orderProduct->product_type;
        $orderRefund->product_id             = $orderProduct->product_id;
        $orderRefund->sku_id                 = $orderProduct->sku_id;
        $orderRefund->title                  = $orderProduct->title;
        $orderRefund->sku_name               = $orderProduct->sku_name;
        $orderRefund->image                  = $orderProduct->image;
        $orderRefund->category_id            = $orderProduct->category_id;
        $orderRefund->brand_id               = $orderProduct->brand_id;
        $orderRefund->product_group_id       = $orderProduct->product_group_id;
        $orderRefund->outer_product_id       = $orderProduct->outer_product_id;
        $orderRefund->outer_sku_id           = $orderProduct->outer_sku_id;
        $orderRefund->barcode                = $orderProduct->barcode;
        $orderRefund->price                  = $orderProduct->price;
        $orderRefund->cost_price             = $orderProduct->cost_price;
        $orderRefund->product_amount         = $orderProduct->product_amount;
        $orderRefund->payable_amount         = $orderProduct->payable_amount;
        $orderRefund->payment_amount         = $orderProduct->payment_amount;
        $orderRefund->divided_payment_amount = $orderProduct->divided_payment_amount;
        $orderRefund->shipping_status        = $orderProduct->shipping_status;
        $orderRefund->unit                   = $orderProduct->unit;
        $orderRefund->unit_quantity          = $orderProduct->unit_quantity;


        // 获取当售后阶段
        $orderRefund->phase = $this->getRefundPhase($orderProduct);
        // 计算退款金额
        $refundAmount = 0;
        if (in_array($orderRefund->refund_type, [
            RefundTypeEnum::REFUND,
            RefundTypeEnum::RETURN_GOODS_REFUND,
        ], true)) {
            $refundAmount = (string) ($orderRefund->refund_amount ?? 0);

            $maxRefundAmount = $orderProduct->maxRefundAmount();

            if (bccomp($refundAmount, 0, 2) <= 0) {
                $refundAmount = $maxRefundAmount;
            }
            if (bccomp($refundAmount, $maxRefundAmount, 2) > 0) {
                $refundAmount = $maxRefundAmount;
            }
        }

        $orderRefund->freight_amount = new Amount(0);
        $orderRefund->refund_amount  = new Amount($refundAmount);

        $orderRefund->total_refund_amount = bcadd(
            $orderRefund->refund_amount->value(),
            $orderRefund->freight_amount->value(),
            2);


        switch ($orderRefund->refund_type) {
            case RefundTypeEnum::RESHIPMENT:
            case RefundTypeEnum::REFUND:
                $orderRefund->has_good_return = false;
                $orderRefund->refund_status   = RefundStatusEnum::WAIT_SELLER_AGREE;
                break;
            case RefundTypeEnum::EXCHANGE:
            case RefundTypeEnum::WARRANTY:
            case RefundTypeEnum::RETURN_GOODS_REFUND:
                $orderRefund->has_good_return = true;
                $orderRefund->refund_status   = RefundStatusEnum::WAIT_SELLER_AGREE_RETURN;
                break;

        }

        $orderRefund->created_time = now();

        // 设置订单项目状态
        $orderProduct->refund_status = $orderRefund->refund_status;
        $orderProduct->refund_id     = $orderRefund->id;
        $order->refunds->add($orderRefund);
        return $orderRefund;
    }


    /**
     * 获取退款售后单阶段
     *
     * @param  OrderProduct  $orderProduct
     *
     * @return RefundPhaseEnum
     */
    protected function getRefundPhase(OrderProduct $orderProduct) : RefundPhaseEnum
    {

        if ($orderProduct->order_status === OrderStatusEnum::FINISHED) {
            return RefundPhaseEnum::AFTER_SALE;
        }
        return RefundPhaseEnum::ON_SALE;
    }

}
