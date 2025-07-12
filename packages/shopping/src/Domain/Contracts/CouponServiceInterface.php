<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Data\CouponInfoData;

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

}