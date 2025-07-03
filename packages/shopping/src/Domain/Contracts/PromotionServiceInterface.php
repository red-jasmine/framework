<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Data\ProductAmount;

interface PromotionServiceInterface
{
    /**
     * 获取商品优惠信息
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactors
     * @param  ProductAmount  $productAmount  优惠金额
     *
     * @return ProductAmount
     */
    public function getProductPromotion(
        ProductPurchaseFactor $productPurchaseFactors,
        ProductAmount $productAmount
    ) : ProductAmount;

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