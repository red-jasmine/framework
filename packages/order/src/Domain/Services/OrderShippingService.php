<?php

namespace RedJasmine\Order\Domain\Services;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Order\Domain\Models\OrderCardKey;


class OrderShippingService
{


    /**
     * @param Order $order
     * @return void
     * @throws OrderException
     */
    protected function validateShipping(Order $order) : void
    {
        if ($order->isAllowShipping() === false) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
        }

    }

    /**
     * 物流发货
     *
     * @param Order $order
     * @param bool $isSplit
     * @param OrderLogistics $logistics
     * @param bool $isFinished
     * @return void
     * @throws OrderException
     */
    public function logistics(Order $order, bool $isSplit, OrderLogistics $logistics, bool $isFinished = true) : void
    {


        $this->validateShipping($order);

        if (!in_array($order->shipping_type, ShippingTypeEnum::allowLogistics(), true)) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
        }

        // 添加物流记录
        $order->addLogistics($logistics);
        $isEffectiveShipping = false;

        $order->products
            ->where('shipping_status', '<>', ShippingStatusEnum::SHIPPED) // 如果不是已发货完成的单
            ->each(function (OrderProduct $orderProduct) use ($isSplit, $isFinished, $logistics, &$isEffectiveShipping) {
                // 如果不是有效单
                if ($orderProduct->isEffective() === false) {
                    return;
                }
                if (($isSplit === false) || ($isSplit === true && in_array($orderProduct->id, $logistics->order_product_id ?? [], false))) {
                    $orderProduct->shipping_status = $isFinished ? ShippingStatusEnum::SHIPPED : ShippingStatusEnum::PART_SHIPPED;
                    $orderProduct->shipping_time   = $orderProduct->shipping_time ?? now();
                    $isEffectiveShipping           = true;
                }

            });

        if ($isEffectiveShipping === false) {
            throw OrderException::newFromCodes(OrderException::NO_EFFECTIVE_SHIPPING, '没有有效发货商品');
        }
        // 计算订单 发货状态
        $order->shipping();

    }


    /**
     * 卡密发货
     *
     * @param Order $order
     * @param OrderCardKey $orderProductCardKey
     *
     * @return void
     * @throws OrderException
     */
    public function cardKey(Order $order, OrderCardKey $orderProductCardKey) : void
    {
        $this->validateShipping($order);

        if ($order->shipping_type !== ShippingTypeEnum::CARD_KEY) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
        }
        /**
         * @var $orderProduct OrderProduct
         */
        $orderProduct = $order->products->where('id', $orderProductCardKey->order_product_id)->firstOrFail();

        if ($orderProduct->shipping_status === ShippingStatusEnum::SHIPPED) {
            dd($orderProduct->shipping_status);
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }

        $orderProductCardKey->order_no    = $order->order_no;
        $orderProductCardKey->entity_type = EntityTypeEnum::ORDER;
        $orderProductCardKey->entity_id   = $order->id;
        $orderProductCardKey->seller_type = $order->seller_type;
        $orderProductCardKey->seller_id   = $order->seller_id;
        $orderProductCardKey->buyer_type  = $order->buyer_type;
        $orderProductCardKey->buyer_id    = $order->buyer_id;

        $orderProduct->addCardKey($orderProductCardKey);


        $orderProduct->shipping_status = ShippingStatusEnum::PART_SHIPPED;
        $orderProduct->shipping_time   = $orderProduct->shipping_time ?? now();

        if ($orderProduct->progress >= $orderProduct->quantity) {
            $orderProduct->shipping_status = ShippingStatusEnum::SHIPPED;
            $orderProduct->signed_time     = now(); // 虚拟商品作为最后一次发货时间
        }

        $order->shipping();
    }


    /**
     * 虚拟发货
     *
     * @param Order $order
     * @param int $orderProductId
     * @param bool $isFinished 是否完成发货
     *
     * @return void
     * @throws OrderException
     */
    public function dummy(Order $order, int $orderProductId, bool $isFinished = true) : void
    {
        $this->validateShipping($order);

        if ($order->shipping_type !== ShippingTypeEnum::DUMMY) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
        }

        $orderProduct = $order->products->where('id', $orderProductId)->firstOrFail();

        if ($orderProduct->shipping_status === ShippingStatusEnum::SHIPPED) {
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }
        $orderProduct->shipping_status = $isFinished ? ShippingStatusEnum::SHIPPED : ShippingStatusEnum::PART_SHIPPED;
        $orderProduct->shipping_time   = $orderProduct->shipping_time ?? now();

        if ($orderProduct->shipping_status === ShippingStatusEnum::SHIPPED) {
            $orderProduct->signed_time = now();
        }

        $order->shipping();
    }

}
