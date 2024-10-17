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
     * @param Order       $order
     * @param OrderRefund $orderRefund
     *
     * @return void
     * @throws RefundException
     */
    public function create(Order $order, OrderRefund $orderRefund) : void
    {
        /**
         * @var $orderProduct OrderProduct
         */
        $orderProduct = $order->products->where('id', $orderRefund->order_product_id)->firstOrFail();

        // 类型是否允许
        if (!in_array($orderRefund->refund_type, $orderProduct->allowRefundTypes(), true)) {
            throw RefundException::newFromCodes(RefundException::REFUND_TYPE_NOT_ALLOW);
        }

        // 填充 子商品单
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
        $orderRefund->seller_category_id     = $orderProduct->seller_category_id;
        $orderRefund->outer_id               = $orderProduct->outer_id;
        $orderRefund->outer_sku_id           = $orderProduct->outer_sku_id;
        $orderRefund->barcode                = $orderProduct->barcode;
        $orderRefund->price                  = $orderProduct->price;
        $orderRefund->cost_price             = $orderProduct->cost_price;
        $orderRefund->product_amount         = $orderProduct->product_amount;
        $orderRefund->payable_amount         = $orderProduct->payable_amount;
        $orderRefund->payment_amount         = $orderProduct->payment_amount;
        $orderRefund->divided_payment_amount = $orderProduct->divided_payment_amount;
        $orderRefund->shipping_status        = $orderProduct->shipping_status;
        // TODO 创建人

        // 获取当售后阶段
        $orderRefund->phase = $this->getRefundPhase($orderProduct);

        // 计算退款金额
        $refundAmount = 0;
        if (in_array($orderRefund->refund_type, [
            RefundTypeEnum::REFUND,
            RefundTypeEnum::RETURN_GOODS_REFUND,
        ],           true)) {
            $refundAmount = (string)($orderRefund->refund_amount ?? 0);
            if (bccomp($refundAmount, 0, 2) <= 0) {
                $refundAmount = $orderProduct->maxRefundAmount();
            }
            if (bccomp($refundAmount, $orderProduct->maxRefundAmount(), 2) > 0) {
                $refundAmount = $orderProduct->maxRefundAmount();
            }
        }
        $orderRefund->refund_amount = new Amount($refundAmount);

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

        $order->refunds->add($orderRefund);
    }


    /**
     * 获取退款售后单阶段
     *
     * @param OrderProduct $orderProduct
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
