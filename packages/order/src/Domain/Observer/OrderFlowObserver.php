<?php

namespace RedJasmine\Order\Domain\Observer;

use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Flows\OrderFlowInterface;
use RedJasmine\Order\Domain\Models\Order;

/**
 * 标准电商流程
 */
class OrderFlowObserver
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
        $flows = config('red-jasmine-order.flows', []);

        $flowClass = $flows[$order->order_type->value] ?? null;
        if (blank($flowClass)) {
            throw new OrderException('流程不支持');
        }
        return app($flowClass);
    }

    /**
     * @param Order $order
     * @return void
     * @throws OrderException
     */
    public function creating(Order $order) : void
    {
        $this->orderFlow($order)->creating($order);
    }

    /**
     * @param Order $order
     * @return void
     * @throws OrderException
     */
    public function paid(Order $order) : void
    {

        $this->orderFlow($order)->paid($order);
    }

    /**
     * @param Order $order
     * @return void
     * @throws OrderException
     */
    public function accept(Order $order) : void
    {
        $this->orderFlow($order)->accept($order);
    }

    /**
     * @param Order $order
     * @return void
     * @throws OrderException
     */
    public function reject(Order $order):void
    {
        $this->orderFlow($order)->reject($order);
    }

    public function shipping(Order $order) : void
    {

        $this->orderFlow($order)->shipping($order);
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
