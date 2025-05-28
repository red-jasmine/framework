<?php

namespace RedJasmine\Order\Domain\Services;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Order\Domain\Models\Refund;

class OrderRefundService
{

    /**
     * @param  Order  $order
     * @param  Refund  $orderRefund
     *
     * @return Refund
     * @throws RefundException
     */
    public function create(Order $order, Refund $orderRefund) : Refund
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

        // 获取当售后阶段
        $orderRefund->phase = $this->getRefundPhase($orderProduct);


        // 如果需要退款
        if (in_array($orderRefund->refund_type, [
            RefundTypeEnum::REFUND,
            RefundTypeEnum::RETURN_GOODS_REFUND,
        ], true)) {
            // 计算最大的商品金额  = 应付金额 - 退款金额
            $maxRefundAmount = $orderProduct->maxRefundProductAmount();

            $orderRefund->refund_product_amount = $orderRefund->refund_product_amount ?? $maxRefundAmount;
            if ($orderRefund->refund_product_amount->compare($maxRefundAmount)) {
                $orderRefund->refund_product_amount = $maxRefundAmount;
            }
            $orderRefund->refund_freight_amount = Money::parse(0, $orderRefund->currency);
        } else {
            // 如果不需要退款
            $orderRefund->refund_product_amount = Money::parse(0, $orderRefund->currency);
            $orderRefund->refund_freight_amount = Money::parse(0, $orderRefund->currency);
        }


        $orderRefund->total_refund_amount = $orderRefund->refund_product_amount->add($orderRefund->refund_freight_amount);


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
        $orderProduct->last_refund_no = $orderRefund->refund_no;
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
