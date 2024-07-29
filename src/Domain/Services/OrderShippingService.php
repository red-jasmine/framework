<?php

namespace RedJasmine\Order\Domain\Services;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Order\Domain\Models\OrderProductCardKey;


class OrderShippingService
{


    /**
     * 物流发货
     *
     * @param Order          $order
     * @param bool           $isSplit
     * @param OrderLogistics $logistics
     *
     * @return void
     * @throws OrderException
     */
    public function logistics(Order $order, bool $isSplit = false, OrderLogistics $logistics) : void
    {
        $types = $order->products->pluck('shipping_type')->unique()->map(fn($item) => $item->value)->flip();


        // 如果存在 物流发货类型的 才能物流发货
        if ($types->hasAny(collect(ShippingTypeEnum::allowLogistics())->map(fn($item) => $item->value)->toArray()) === false) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
        }

        // 如果不是拆分发货 那么必须所有商品都是 快递发货类型
        if ($isSplit === false) {
            $otherTypes = $types->keys()->diff(collect(ShippingTypeEnum::allowLogistics())->map(fn($item) => $item->value));
            if ($otherTypes->count()) {
                throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '存在不同的发货类型请拆分发货');
            }
        }
        // 添加物流记录
        $order->addLogistics($logistics);
        $isEffectiveShipping = false;
        // 设置 订单商品未发货状态
        $order->products
            ->whereIn('shipping_status', [ ShippingStatusEnum::NIL, ShippingStatusEnum::WAIT_SEND ])
            ->each(function (OrderProduct $orderProduct) use ($isSplit, $logistics, &$isEffectiveShipping) {
                if ($orderProduct->isEffective() === false) {
                    return;
                }
                if (($isSplit === false) || ($isSplit === true && in_array($orderProduct->id, $logistics->order_product_id ?? [], true))) {
                    // 发货必须是允许物流发货的类型
                    if (!in_array($orderProduct->shipping_type, ShippingTypeEnum::allowLogistics(), true)) {
                        throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
                    }
                    $orderProduct->shipping_status = ShippingStatusEnum::SHIPPED;
                    $orderProduct->shipping_time   = now();
                }
                $isEffectiveShipping = true;
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
     * @param Order               $order
     * @param OrderProductCardKey $orderProductCardKey
     *
     * @return void
     * @throws OrderException
     */
    public function cardKey(Order $order, OrderProductCardKey $orderProductCardKey) : void
    {

        $orderProduct = $order->products->where('id', $orderProductCardKey->order_product_id)->firstOrFail();

        if ($orderProduct->shipping_type !== ShippingTypeEnum::CDK) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
        }

        if ($orderProduct->shipping_status === ShippingStatusEnum::SHIPPED) {
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }


        $orderProduct->addCardKey($orderProductCardKey);

        $orderProduct->shipping_status = ShippingStatusEnum::PART_SHIPPED;
        $orderProduct->shipping_time   = $orderProduct->shipping_time ?? now();


        if ($orderProduct->progress >= $orderProduct->num) {
            $orderProduct->shipping_status = ShippingStatusEnum::SHIPPED;
            $orderProduct->collect_time    = now(); // 虚拟商品作为最后一次发货时间
        }

        $order->shipping();
    }


    /**
     * 虚拟发货
     *
     * @param Order $order
     * @param int   $orderProductId
     * @param bool  $isFinished 是否完成发货
     *
     * @return void
     * @throws OrderException
     */
    public function virtual(Order $order, int $orderProductId, bool $isFinished = true) : void
    {
        $orderProduct = $order->products->where('id', $orderProductId)->firstOrFail();

        if ($orderProduct->shipping_type !== ShippingTypeEnum::VIRTUAL) {
            throw OrderException::newFromCodes(OrderException::SHIPPING_TYPE_NOT_ALLOW, '发货类型不支持操作');
        }

        if ($orderProduct->shipping_status === ShippingStatusEnum::SHIPPED) {
            throw OrderException::newFromCodes(OrderException::ORDER_STATUS_NOT_ALLOW);
        }
        $orderProduct->shipping_status = $isFinished ? ShippingStatusEnum::SHIPPED : ShippingStatusEnum::PART_SHIPPED;
        $orderProduct->shipping_time   = $orderProduct->shipping_time ?? now();
        if ($orderProduct->shipping_status === ShippingStatusEnum::SHIPPED) {
            $orderProduct->collect_time = now(); // 虚拟商品作为最后一次发货时间
        }
        $order->shipping();
    }

}
