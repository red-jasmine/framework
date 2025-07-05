<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Shopping\Domain\Data\OrderAmountData;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Shopping\Domain\Data\OrderProductData;
use RedJasmine\Shopping\Domain\Data\OrdersData;
use RedJasmine\Shopping\Domain\Hooks\ShoppingOrderCreateHook;

/**
 * 订单结算服务
 */
class OrderDomainService extends AmountCalculationService
{


    /**
     * @param  OrderData  $orderData
     *
     * @return OrdersData
     */
    protected function orderSplit(OrderData $orderData) : OrdersData
    {
        $orders       = [];
        $productGroup = collect($orderData->products)->groupBy(function (OrderProductData $product) {
            return $product->getSplitKey();
        });

        $ordersData = new OrdersData();
        foreach ($productGroup as $products) {
            $order           = clone $orderData;
            $order->seller   = $products[0]->product->seller;
            $order->products = [...$products];
            $orders[]        = $order;
        }
        $ordersData->setOrders($orders);
        return $ordersData;
    }


    protected function init(OrderData $orderData) : OrdersData
    {
        // 获取商品信息
        foreach ($orderData->products as $product) {
            // 生成序列号
            $product->buildSerialNumber();
            // 获取订单拆分key
            $product->setSplitKey(
                $this->orderService->getOrderProductSplitKey($product)
            );
        }

        return $this->orderSplit($orderData);
    }

    /**
     * @param  OrderData  $orderData
     *
     * @return OrdersData
     */
    public function buy(OrderData $orderData) : OrdersData
    {

        $ordersData = $this->init($orderData);
        foreach ($ordersData->orders as $orderDataItem) {
            $orderDataItem->setOrderAmount(
                $this->calculates($orderDataItem)
            );
        }
        // 对订单进行排序 TODO
        // 对商品进行排序

        foreach ($ordersData->orders as $orderDataItem) {


            // 调用库存服务 进行扣减
            $orderDataItem = ShoppingOrderCreateHook::hook($orderDataItem, fn() => $this->orderService->create($orderDataItem));
            $orderDataItem->setKey($orderDataItem->getOrderNo());

            // 扣减库存
            /**
             * @var OrderData $orderDataItem
             */
            foreach ($orderDataItem->products as $productDataItem) {
                $this->stockService->lockStock($productDataItem->product, $productDataItem->quantity, $productDataItem->getOrderProductNo());
            }


        }


        $ordersData->total();
        return $ordersData;
    }

    public function check(OrderData $orderData) : OrdersData
    {
        $ordersData = $this->init($orderData);
        foreach ($ordersData->orders as $orderDataItem) {
            $orderDataItem->setOrderAmount(
                $this->calculates($orderDataItem)
            );
        }
        $ordersData->total();
        return $ordersData;
    }

    protected function calculates(OrderData $orderData) : OrderAmountData
    {
        foreach ($orderData->products as $index => $product) {
            $product->setKey($index);
            $product->buyer = $orderData->buyer;
        }
        return $this->getOrderAmount($orderData->products);

    }
}
