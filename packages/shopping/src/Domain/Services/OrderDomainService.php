<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Shopping\Domain\Data\OrderAmountData;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Shopping\Domain\Data\OrderProductData;
use RedJasmine\Shopping\Domain\Data\OrdersData;

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

    /**
     * @param  OrderData  $orderData
     *
     * @return OrdersData
     */
    public function buy(OrderData $orderData) : OrdersData
    {
        // 获取商品信息
        foreach ($orderData->products as $product) {
            // 获取商品信息
            $productInfo = $this->productService->getProductInfo($product);

            $product->setProductInfo($productInfo);
            // 获取价格信息
            $productAmount = $this->productService->getProductAmount($product);
            $product->setProductAmount($productAmount);
            // 获取库存信息
            $stockInfo = $this->stockService->getStockInfo($product->product, $product->quantity);
            $product->setStockInfo($stockInfo);

            // 获取订单拆分key
            $product->setSplitKey(
                $this->orderService->getOrderProductSplitKey($product)
            );
            // 获取商品优惠信息 TODO

        }

        $ordersData = $this->orderSplit($orderData);

        foreach ($ordersData->orders as $orderDataItem) {

            $orderDataItem->setOrderAmount(
                $this->calculates($orderDataItem)
            );

            $orderNo = $this->orderService->create($orderDataItem);
            $orderDataItem->setKey($orderNo);
        }

        return $ordersData;
    }

    public function calculates(OrderData $orderData) : OrderAmountData
    {
        foreach ($orderData->products as $index => $product) {
            $product->setKey($index);
            $product->buyer = $orderData->buyer;
        }
        return $this->getOrderAmount($orderData->products);

    }
}
