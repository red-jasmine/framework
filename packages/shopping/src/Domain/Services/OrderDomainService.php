<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponUsageData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderAmountInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderProductData;
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
    public function buy(OrderData $orderData) : OrdersData
    {

        $ordersData = $this->initOrders($orderData);

        foreach ($ordersData->orders as $orderDataItem) {
            $this->calculateOrderAmount($orderDataItem);
        }

        // 扣减跨店铺优惠券 TODO
        foreach ($ordersData->orders as $orderDataItem) {
            // 调用库存服务 进行扣减
            $orderDataItem = ShoppingOrderCreateHook::hook($orderDataItem, fn() => $this->orderService->create($orderDataItem));
            $orderDataItem->setKey($orderDataItem->getOrderNo());

            // 扣减库存
            /**
             * @var OrderData $orderDataItem
             */
            foreach ($orderDataItem->products as $productDataItem) {
                $this->stockService->lockStock(
                    $productDataItem->product,
                    $productDataItem->quantity,
                    $productDataItem->getOrderProductNo()
                );
            }

            //  核销商品级别使用的优惠券
            foreach ($orderDataItem->products as $productDataItem) {
                // 扣减商品优惠券
                foreach ($productDataItem->getProductInfo()->getProductAmountInfo()->coupons as $coupon) {
                    $usages   = [];
                    $usages[] = CouponUsageData::from([
                        'orderType'      => 'order',
                        'orderNo'        => $orderDataItem->getOrderNo(),
                        'orderProductNo' => $productDataItem->getOrderProductNo(),
                        'discountAmount' => $coupon->discountAmount
                    ]);

                    $this->couponService->useCoupon($coupon->couponNo, $usages);
                }

            }
            // 核销订单级别使用的优惠券
            foreach ($orderDataItem->getOrderAmountInfo()->coupons as $coupon) {
                $usages   = [];
                $usages[] = CouponUsageData::from([
                    'orderType'      => 'order',
                    'orderNo'        => $orderDataItem->getOrderNo(),
                    'orderProductNo' => null,
                    'discountAmount' => $coupon->discountAmount
                ]);
                $this->couponService->useCoupon($coupon->couponNo, $usages);
            }
        }
        $ordersData->statistics();
        return $ordersData;
    }

    protected function initOrders(OrderData $orderData) : OrdersData
    {
        foreach ($orderData->products as $productDataItem) {
            $productDataItem->buyer = $orderData->buyer;
        }
        $orderData->products = $this->init($orderData->products);

        return $this->orderSplit($orderData);
    }

    /**
     * @param  OrderData  $orderData
     *
     * @return OrdersData
     */
    protected function orderSplit(OrderData $orderData) : OrdersData
    {
        $orders       = [];
        $productGroup = collect($orderData->products)->groupBy(function (OrderProductData $product) {
            return $product->getProductInfo()->getSplitKey();
        });
        // 对订单进行排序 TODO
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


    public function check(OrderData $orderData) : OrdersData
    {

        // 拆分订单
        $ordersData = $this->initOrders($orderData);

        foreach ($ordersData->orders as $orderDataItem) {
            $this->calculateOrderAmount($orderDataItem);
        }
        $ordersData->statistics();
        return $ordersData;
    }


}
