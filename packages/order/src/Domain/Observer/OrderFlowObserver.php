<?php

namespace RedJasmine\Order\Domain\Observer;

use RedJasmine\Order\Domain\Models\Order;

/**
 * 标准电商流程
 */
class OrderFlowObserver
{
    // 根据不同的 订单类型 走不同的策略


    /**
     * @param  Order  $order
     *
     * @return void
     */
    public function creating(Order $order) : void
    {
        $order->getOrderTypeStrategy()->creating($order);

    }

    /**
     * @param  Order  $order
     *
     * @return void
     */
    public function paid(Order $order) : void
    {
        $order->getOrderTypeStrategy()->paid($order);
    }

    /**
     * @param  Order  $order
     *
     * @return void
     */
    public function accept(Order $order) : void
    {
        $order->getOrderTypeStrategy()->accept($order);
    }

    /**
     * @param  Order  $order
     *
     * @return void
     */
    public function reject(Order $order) : void
    {
        $order->getOrderTypeStrategy()->reject($order);
    }

    public function shipping(Order $order) : void
    {

        $order->getOrderTypeStrategy()->shipping($order);
    }

    public function shipped(Order $order) : void
    {

        $order->getOrderTypeStrategy()->shipped($order);
    }

    /**
     * 订单确认
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function confirmed(Order $order) : void
    {

        $order->getOrderTypeStrategy()->confirmed($order);

    }

}
