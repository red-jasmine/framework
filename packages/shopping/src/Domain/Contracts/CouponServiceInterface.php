<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponUsageData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;

interface CouponServiceInterface
{

    /**
     * 获取用户商品级别优惠券
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return CouponInfoData[]
     */
    public function getUserCouponsByProduct(ProductPurchaseFactor $productPurchaseFactor) : array;


    /**
     * 获取可用的订单级别优惠券
     *
     * @param  OrderData  $orderData
     *
     * @return CouponInfoData[]
     */
    public function getUserCouponsByOrder(OrderData $orderData) : array;

    /**
     * @param  string  $couponNo
     * @param  CouponUsageData[]  $usages
     *
     * @return bool
     */
    public function useCoupon(string $couponNo, array $usages) : bool;
}