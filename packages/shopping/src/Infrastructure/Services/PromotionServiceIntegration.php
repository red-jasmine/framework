<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;

class PromotionServiceIntegration implements PromotionServiceInterface
{
    /**
     * 获取商品级别优惠信息
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor  商品购买因子
     * @param  ProductAmountInfo  $productAmount  优惠金额
     *
     * @return ProductAmountInfo
     */
    public function getProductPromotion(
        ProductPurchaseFactor $productPurchaseFactor,
        ProductAmountInfo $productAmount
    ) : ProductAmountInfo {

        // 根据 促销 系统 获取优惠金额
        // 添加优惠
        //$productAmount->discountAmount = $productAmount->totalPrice->multiply(0.1);
        // 可以免服务费
        //$productAmount->serviceAmount = $productAmount->serviceAmount->subtract($productAmount->serviceAmount);
        // 可以免税

        return $productAmount;
    }


    /**
     * 获取购物车级优惠
     *
     * @param  array  $cartItems
     * @param  float  $totalAmount
     *
     * @return array
     */
    public function getOrderPromotion(array $cartItems, float $totalAmount) : array
    {
        // TODO: Implement getCartPromotion() method.
    }

    /**
     * 获取用户优惠券
     *
     * @param  string  $userId
     * @param  float  $totalAmount
     *
     * @return array
     */
    public function getUserCoupons(string $userId, float $totalAmount) : array
    {
        // TODO: Implement getUserCoupons() method.
    }

    /**
     * 计算优惠金额
     *
     * @param  array  $promotions
     * @param  float  $totalAmount
     *
     * @return float
     */
    public function calculateDiscountAmount(array $promotions, float $totalAmount) : float
    {
        // TODO: Implement calculateDiscountAmount() method.
    }


}