<?php

namespace RedJasmine\Order\Domain;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShippableTypeEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderAddress;
use RedJasmine\Order\Domain\Models\OrderInfo;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Order\Domain\Models\OrderProductCardKey;
use RedJasmine\Order\Domain\Models\OrderProductInfo;
use RedJasmine\Order\Domain\Models\OrderRefund;
use RedJasmine\Support\Helpers\ID\Snowflake;

class OrderFactory
{
    /**
     * @return int
     * @throws Exception
     */
    public function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


    /**
     * @return Order
     * @throws Exception
     */
    public function createOrder() : Order
    {
        $order         = new Order();
        $order->id     = $this->buildID();
        $orderInfo     = new OrderInfo();
        $orderInfo->id = $order->id;
        $order->setRelation('info', $orderInfo);
        $order->setRelation('products', new Collection([]));
        $order->setRelation('payments', new Collection([]));
        $order->setRelation('address', null);
        return $order;
    }


    /**
     * @return OrderProduct
     * @throws Exception
     */
    public function createOrderProduct() : OrderProduct
    {
        $orderProduct     = new OrderProduct();
        $orderProduct->id = $this->buildID();
        $info             = new OrderProductInfo();
        $info->id         = $orderProduct->id;
        $orderProduct->setRelation('info', $info);
        return $orderProduct;
    }


    /**
     * @return OrderAddress
     */
    public function createOrderAddress() : OrderAddress
    {
        return new OrderAddress();
    }


    /**
     * @return OrderPayment
     * @throws Exception
     */
    public function createOrderPayment() : OrderPayment
    {
        $payment     = new OrderPayment();
        $payment->id = $this->buildID();
        return $payment;
    }

    /**
     * @return OrderLogistics
     * @throws Exception
     */
    public function createOrderLogistics() : OrderLogistics
    {
        $orderLogistics                 = new OrderLogistics();
        $orderLogistics->id             = $this->buildID();
        $orderLogistics->shippable_type = LogisticsShippableTypeEnum::ORDER;
        return $orderLogistics;
    }

    /**
     * @return OrderProductCardKey
     * @throws Exception
     */
    public function createOrderProductCardKey() : OrderProductCardKey
    {
        $orderProductCardKey     = new OrderProductCardKey();
        $orderProductCardKey->id = $this->buildID();
        return $orderProductCardKey;
    }


    /**
     * @param Order $order
     *
     * @return OrderRefund
     * @throws Exception
     */
    public function createRefund(Order $order) : OrderRefund
    {
        $orderRefund           = new OrderRefund();
        $orderRefund->id       = $this->buildID();
        $orderRefund->order_id = $order->id;

        return $orderRefund;
    }

}
