<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Data\CouponInfoData;
use RedJasmine\Shopping\Domain\Data\CouponUsageData;

interface CouponServiceInterface
{

    /**
     * 获取用户商品级别优惠券
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return array|CouponInfoData[]
     */
    public function getUserCouponsByProduct(ProductPurchaseFactor $productPurchaseFactor) : array;


    /**
     * @param  string  $couponNo
     * @param  CouponUsageData[]  $usages
     *
     * @return bool
     */
    public function useCoupon(string $couponNo, array $usages) : bool;
}