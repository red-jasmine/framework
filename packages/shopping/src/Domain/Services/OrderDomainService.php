<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponUsageData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderProductData;
use RedJasmine\Ecommerce\Domain\Data\OrdersData;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
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

        $ordersData = $this->check($orderData);

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
            // 核销订单级别使用的优惠券
            foreach ($orderDataItem->getOrderAmountInfo()->coupons as $coupon) {
                if ($coupon->discountLevel !== DiscountLevelEnum::ORDER) {
                    continue;
                }
                $usages   = [];
                $usages[] = CouponUsageData::from([
                    'orderType'      => 'order',
                    'orderNo'        => $orderDataItem->getOrderNo(),
                    'orderProductNo' => null,
                    'discountAmount' => $coupon->discountAmount
                ]);
                $this->couponService->useCoupon($coupon->couponNo, $usages);
            }
            //  核销商品级别使用的优惠券
            foreach ($orderDataItem->products as $productDataItem) {
                // 扣减商品优惠券
                foreach ($productDataItem->getProductInfo()->getProductAmountInfo()->coupons as $coupon) {

                    if ($coupon->discountLevel !== DiscountLevelEnum::PRODUCT) {
                        continue;
                    }
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

        }


        // 扣减跨店铺优惠券 TODO
        foreach ($ordersData->coupons as $coupon) {
            if ($coupon->discountLevel !== DiscountLevelEnum::CHECKOUT) {
                continue;
            }
            $usages = [];
            foreach ($ordersData->orders as $orderDataItem) {

                foreach ($orderDataItem->getOrderAmountInfo()->coupons as $orderCoupon) {
                    if ($orderCoupon->couponNo === $coupon->couponNo) {
                        $usages[] = CouponUsageData::from([
                            'orderType'      => 'order',
                            'orderNo'        => $orderDataItem->getOrderNo(),
                            'orderProductNo' => null,
                            'discountAmount' => $orderCoupon->discountAmount
                        ]);
                    }

                }


            }
            $this->couponService->useCoupon($coupon->couponNo, $usages);
        }
        $ordersData->statistics();
        return $ordersData;
    }

    protected function initOrders(OrderData $orderData) : OrdersData
    {
        // 设置商品卖家信息
        foreach ($orderData->products as $productDataItem) {
            $productDataItem->buyer = $orderData->buyer;
        }
        // 对商品信息进行初始化
        $orderData->products = $this->init($orderData->products);
        // 对订单拆分
        $ordersData = $this->orderSplit($orderData);
        // 对单个订进行结算
        foreach ($ordersData->orders as $orderDataItem) {
            $this->calculateOrderAmount($orderDataItem);
        }
        // 对所有订单计算 店铺的优惠券 TODO


        $this->handleCheckoutCoupons($ordersData);


        return $ordersData;
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
            $ordersData->buyer = $orderData->buyer;
            $order             = clone $orderData;
            $order->seller     = $products[0]->product->seller;
            $order->products   = [...$products];
            $orders[]          = $order;
        }
        $ordersData->setOrders($orders);
        return $ordersData;
    }


    public function check(OrderData $orderData) : OrdersData
    {

        // 拆分订单
        $ordersData = $this->initOrders($orderData);

        $ordersData->statistics();
        return $ordersData;
    }


}
