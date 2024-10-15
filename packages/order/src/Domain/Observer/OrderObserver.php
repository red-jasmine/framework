<?php

namespace RedJasmine\Order\Domain\Observer;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Strategies\OrderFlowInterface;

/**
 * 标准电商流程
 */
class OrderObserver
{
    // 根据不同的 订单类型 走不同的策略


    /**
     * @param Order $order
     *
     * @return OrderFlowInterface
     * @throws OrderException
     */
    protected function orderFlow(Order $order) : OrderFlowInterface
    {
        $flows     = config('red-jasmine.order.flows', []);
        $flowClass = $flows[$order->order_type->value] ?? null;
        if (blank($flowClass)) {
            throw new OrderException('流程不支持');
        }
        return app($flowClass);
    }

    public function creating(Order $order) : void
    {

        $this->orderFlow($order)->creating($order);

    }

    public function paid(Order $order) : void
    {
        $this->orderFlow($order)->paid($order);

    }

    public function shipped(Order $order) : void
    {
        $this->orderFlow($order)->shipped($order);
    }

    /**
     * 订单确认
     *
     * @param Order $order
     *
     * @return void
     * @throws OrderException
     */
    public function confirmed(Order $order) : void
    {
        $this->orderFlow($order)->confirmed($order);
    }

}
