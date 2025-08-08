<?php

namespace RedJasmine\Order\Domain\Services;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Order\Domain\Exceptions\RefundException;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Order\Domain\Models\Refund;

class OrderRefundService
{


    protected function isNeedRefund(Refund $orderRefund) : bool
    {
        return in_array($orderRefund->refund_type, [
            RefundTypeEnum::REFUND,
            RefundTypeEnum::RETURN_GOODS_REFUND,
        ], true);
    }

    protected function hasRefundFreightAmount(Refund $orderRefund) : bool
    {

        $product = $orderRefund->product;
        // 如果订单 已经发货了  ,那么就不需要退邮费
        if (!in_array($product->shipping_status, [
            null,
            ShippingStatusEnum::WAITING,
            ShippingStatusEnum::PENDING,
        ], true)) {
            return false;
        }


        return true;
    }

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
        if ($this->isNeedRefund($orderRefund)) {
            // 计算最大的商品金额  = 应付金额 - 退款金额
            $maxRefundAmount                    = $orderProduct->maxRefundProductAmount();
            $orderRefund->refund_product_amount = $orderRefund->refund_product_amount ?? $maxRefundAmount;
            if ($orderRefund->refund_product_amount->compare($maxRefundAmount)) {
                $orderRefund->refund_product_amount = $maxRefundAmount;
            }


            // 计算邮费
            $orderRefund->refund_freight_amount = $orderRefund->refund_freight_amount ?? Money::parse(0, $orderRefund->currency);
            if ($this->hasRefundFreightAmount($orderRefund)) {

                if ($orderRefund->refund_freight_amount->isZero()) {
                    $orderRefund->refund_freight_amount = $orderRefund->freight_amount;
                }
                if ($orderRefund->refund_freight_amount->compare($orderRefund->freight_amount) >= 0) {
                    $orderRefund->refund_freight_amount = $orderRefund->freight_amount;
                }


            } else {

                $orderRefund->refund_freight_amount = Money::parse(0, $orderRefund->currency);
            }

            // 计算是否退款是否需要退邮费
            // 什么情况下退邮费
        } else {
            // 如果不需要退款
            $orderRefund->refund_product_amount = Money::parse(0, $orderRefund->currency);
            $orderRefund->refund_freight_amount = Money::parse(0, $orderRefund->currency);
        }

        // 计算退款总金额
        $orderRefund->total_refund_amount = $orderRefund->refund_product_amount->add($orderRefund->refund_freight_amount);


        switch ($orderRefund->refund_type) {
            case RefundTypeEnum::RESHIPMENT:
            case RefundTypeEnum::REFUND:
                $orderRefund->has_good_return = false;
                $orderRefund->refund_status   = RefundStatusEnum::PENDING;
                break;
            case RefundTypeEnum::EXCHANGE:
            case RefundTypeEnum::WARRANTY:
            case RefundTypeEnum::RETURN_GOODS_REFUND:
                $orderRefund->has_good_return = true;
                $orderRefund->refund_status   = RefundStatusEnum::PENDING;
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
