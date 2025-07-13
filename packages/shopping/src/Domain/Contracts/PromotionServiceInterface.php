<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;

interface PromotionServiceInterface
{
    /**
     * 获取商品优惠信息
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactors
     * @param  ProductAmountInfo  $productAmount  优惠金额
     *
     * @return ProductAmountInfo
     */
    public function getProductPromotion(
        ProductPurchaseFactor $productPurchaseFactors,
        ProductAmountInfo $productAmount
    ) : ProductAmountInfo;

    /**
     * 获取购物车级优惠
     *
     * @param  array  $cartItems
     * @param  float  $totalAmount
     *
     * @return array
     */
    public function getOrderPromotion(array $cartItems, float $totalAmount) : array;

    /**
     * 获取用户优惠券
     *
     * @param  string  $userId
     * @param  float  $totalAmount
     *
     * @return array
     */
    public function getUserCoupons(string $userId, float $totalAmount) : array;

    /**
     * 计算优惠金额
     *
     * @param  array  $promotions
     * @param  float  $totalAmount
     *
     * @return float
     */
    public function calculateDiscountAmount(array $promotions, float $totalAmount) : float;
} 