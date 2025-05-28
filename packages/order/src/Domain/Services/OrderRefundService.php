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

        // 计算退款金额
        $refundAmount = Money::parse('0', $orderProduct->currency);
        if (in_array($orderRefund->refund_type, [
            RefundTypeEnum::REFUND,
            RefundTypeEnum::RETURN_GOODS_REFUND,
        ], true)) {

            // 获取最大退款金额
            //$refundAmount = (string) ($orderRefund->refund_amount ?? 0);

            $maxRefundAmount = $orderProduct->maxRefundAmount();

            $refundAmount = $maxRefundAmount;

            // if (bccomp($refundAmount, 0, 2) <= 0) {
            //     $refundAmount = $maxRefundAmount;
            // }
            // if (bccomp($refundAmount, $maxRefundAmount, 2) > 0) {
            //     $refundAmount = $maxRefundAmount;
            // }
        }

        // TODO 计算运费
        $orderRefund->freight_amount = Money::parse(0, $orderProduct->currency);
        $orderRefund->refund_amount  = $refundAmount;

        $orderRefund->total_refund_amount = $orderRefund->refund_amount->add($orderRefund->freight_amount);


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
