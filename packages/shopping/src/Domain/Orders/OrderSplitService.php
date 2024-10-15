<?php

namespace RedJasmine\Shopping\Domain\Orders;

use RedJasmine\Shopping\Domain\Orders\Data\OrderData;
use RedJasmine\Shopping\Domain\Orders\Data\OrdersData;
use RedJasmine\Shopping\Domain\Orders\Data\ProductData;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderSplitProductHook;
use RedJasmine\Support\Foundation\Service\Service;

class OrderSplitService extends Service
{


    /**
     * 拆分订单
     *
     * @param  OrderData  $orderData
     *
     * @return OrdersData
     */
    public function split(OrderData $orderData) : OrdersData
    {
        // 拆分订单
        $orders       = new OrdersData();
        $orderCollect = collect();

        $productGroup = $orderData->products
            ->each(function ($product) {
                // 订单拆分 默认区分卖家来拆分订单
                $product->setSplitKey(ShoppingOrderSplitProductHook::hook($product,
                    fn() => $this->getProductSplitKey($product))
                );
            })
            ->groupBy(function ($product) {
                return $product->getSplitKey();
            })->all();

        foreach ($productGroup as $splitKey => $products) {
            $order  = clone $orderData;
            $seller = $products[0]->getProduct()->owner;
            $order->setSeller($seller);
            $order->products = collect($products);
            $orderCollect->push($order);
        }
        $orders->setOrders($orderCollect);

        return $orders;
    }

    protected function getProductSplitKey(ProductData $productData) : string
    {
        $implode = [
            $productData->getProduct()->owner->getType(),
            $productData->getProduct()->owner->getID(),
        ];
        // 判断是否存特殊的逻辑
        return implode('|', $implode);
    }
}
